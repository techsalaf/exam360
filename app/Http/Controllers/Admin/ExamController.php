<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\Category;
use App\Models\Plan;
use App\Models\SystemSetting;
use App\Helpers\PublicStorageMirror;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Services\AI\ExamGeneratorService;
use Carbon\Carbon;

class ExamController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view_exams')->only(['index', 'questions']);
        $this->middleware('permission:create_exams')->only(['store', 'generate']);
        $this->middleware('permission:edit_exams')->only(['update']);
        $this->middleware('permission:delete_exams')->only(['destroy', 'bulkDestroy']);
    }

    public function index(Request $request)
    {
        $query = Exam::with(['category', 'plan'])->withCount('questions');

        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('title', 'like', '%' . $searchTerm . '%')
                  ->orWhere('slug', 'like', '%' . $searchTerm . '%');
            });
        }

        $exams = $query->latest()->paginate(10);

        $kpi = [
            'total'    => Exam::count(),
            'active'   => Exam::where('is_active', true)->count(),
            'upcoming' => Exam::where('is_active', false)->count(),
        ];

        $categories = Category::where('is_active', true)->orderBy('name')->get();
        $plans = Plan::where('is_active', true)->orderBy('name')->get();

        $settings = SystemSetting::getSettings();
        $currencySymbol = $settings['currency_symbol'] ?? '$';

        return view('admin.exams.index', compact('exams', 'kpi', 'categories', 'plans', 'currencySymbol'));
    }

    public function generate(Request $request, ExamGeneratorService $aiService)
    {
        $request->validate([
            'prompt'   => 'required|string|min:3|max:500',
            'provider' => 'required|in:custom,gemini'
        ]);

        $result = $aiService->generate($request->prompt, $request->provider);

        if ($result['status'] === 'error') {
            return response()->json($result, 422);
        }

        return response()->json($result);
    }

    public function store(Request $request)
    {
        $isPaid = ($request->pricing_type === 'paid' || $request->filled('plan_id'));
        
        $request->merge([
            'is_paid' => $isPaid
        ]);

        $validator = Validator::make($request->all(), [
            'title'                => 'required|string|max:255|unique:exams,title',
            'category_id'          => 'required|exists:categories,id',
            'duration'             => 'required|integer|min:1',
            'pass_percentage'      => 'required|numeric|min:1|max:100',
            'pricing_type'         => 'required|in:free,paid',
            'price'                => 'nullable|required_if:pricing_type,paid|numeric|min:0',
            'start_date'           => 'nullable|date|before:2038-01-01',
            'end_date'             => 'nullable|date|before:2038-01-01',
            'result_date'          => 'nullable|date|before:2038-01-01',
            'plan_id'              => 'nullable|exists:plans,id'
        ]);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json(['status' => 'error', 'errors' => $validator->errors()], 422);
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $data = [
            'title'                => $request->title,
            'slug'                 => Str::slug($request->title) . '-' . rand(1000, 9999),
            'category_id'          => $request->category_id,
            'duration_minutes'     => $request->duration, 
            'pass_percentage'      => $request->pass_percentage,
            'total_marks'          => $request->filled('total_marks') ? $request->total_marks : 0,
            'start_date'           => $request->filled('start_date') ? Carbon::parse($request->start_date)->format('Y-m-d H:i:s') : null,
            'end_date'             => $request->filled('end_date') ? Carbon::parse($request->end_date)->format('Y-m-d H:i:s') : null,
            'result_date'          => $request->filled('result_date') ? Carbon::parse($request->result_date)->format('Y-m-d H:i:s') : null,
            'is_paid'              => $request->is_paid,
            'price'                => ($request->is_paid && $request->pricing_type === 'paid') ? $request->price : 0,
            'plan_id'              => $request->filled('plan_id') ? $request->plan_id : null,
            'is_active'            => true,
            'allow_retake'         => true,
            'description'          => $request->filled('description') ? $request->description : null,
        ];

        DB::beginTransaction();

        try {
            if ($request->hasFile('banner')) {
                $path = $request->file('banner')->store('exams', 'public');
                PublicStorageMirror::sync($path);
                $data['banner'] = $path;
            }

            $exam = Exam::create($data);
            
            DB::commit();

            if ($request->ajax()) {
                return response()->json([
                    'status'       => 'success',
                    'message'      => __('exams.create_success'),
                    'exam_id'      => $exam->id,
                    'exam_title'   => $exam->title,
                    'redirect_url' => route('admin.exams.questions', $exam->id)
                ]);
            }

            return redirect()->back()->with('success', __('exams.create_success'));

        } catch (\Exception $e) {
            DB::rollBack();
            $errorMsg = 'Database Error: ' . $e->getMessage();
            if ($request->ajax()) {
                return response()->json(['status' => 'error', 'message' => $errorMsg], 500);
            }
            return redirect()->back()->with('error', $errorMsg)->withInput();
        }
    }

    public function update(Request $request, $id)
    {
        $exam = Exam::findOrFail($id);

        $isPaid = ($request->pricing_type === 'paid' || $request->filled('plan_id'));

        $request->merge([
            'is_paid' => $isPaid
        ]);

        $validator = Validator::make($request->all(), [
            'title'                => 'required|string|max:255|unique:exams,title,' . $exam->id,
            'category_id'          => 'required|exists:categories,id',
            'duration'             => 'required|integer|min:1',
            'pass_percentage'      => 'required|numeric|min:1|max:100',
            'pricing_type'         => 'required|in:free,paid',
            'price'                => 'nullable|required_if:pricing_type,paid|numeric|min:0',
            'start_date'           => 'nullable|date|before:2038-01-01',
            'end_date'             => 'nullable|date|before:2038-01-01',
            'result_date'          => 'nullable|date|before:2038-01-01',
            'plan_id'              => 'nullable|exists:plans,id'
        ]);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json(['status' => 'error', 'errors' => $validator->errors()], 422);
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $data = [
            'title'                => $request->title,
            'slug'                 => Str::slug($request->title),
            'category_id'          => $request->category_id,
            'duration_minutes'     => $request->duration,
            'pass_percentage'      => $request->pass_percentage,
            'total_marks'          => $request->filled('total_marks') ? $request->total_marks : 0,
            'is_paid'              => $request->is_paid,
            'price'                => ($request->is_paid && $request->pricing_type === 'paid') ? $request->price : 0,
            'plan_id'              => $request->filled('plan_id') ? $request->plan_id : null,
            'start_date'           => $request->filled('start_date') ? Carbon::parse($request->start_date)->format('Y-m-d H:i:s') : null,
            'end_date'             => $request->filled('end_date') ? Carbon::parse($request->end_date)->format('Y-m-d H:i:s') : null,
            'result_date'          => $request->filled('result_date') ? Carbon::parse($request->result_date)->format('Y-m-d H:i:s') : null,
            'description'          => $request->filled('description') ? $request->description : null,
        ];

        try {
            if ($request->hasFile('banner')) {
                if ($exam->banner) {
                    $publicOld = public_path('storage/' . $exam->banner);
                    if (file_exists($publicOld)) {
                        @unlink($publicOld);
                    }
                    if (Storage::disk('public')->exists($exam->banner)) {
                        Storage::disk('public')->delete($exam->banner);
                    }
                }
                
                $path = $request->file('banner')->store('exams', 'public');
                PublicStorageMirror::sync($path);
                $data['banner'] = $path;
            }

            $exam->update($data);

            if ($request->ajax()) {
                return response()->json(['status' => 'success', 'message' => __('exams.update_success')]);
            }

        } catch (\Exception $e) {
            $errorMsg = 'Database Error: ' . $e->getMessage();
            if ($request->ajax()) {
                return response()->json(['status' => 'error', 'message' => $errorMsg], 500);
            }
            return redirect()->back()->with('error', $errorMsg)->withInput();
        }

        return redirect()->back()->with('success', __('exams.update_success'));
    }

    public function toggleStatus($id)
    {
        $exam = Exam::findOrFail($id);
        $exam->update(['is_active' => !$exam->is_active]);

        return redirect()->back()->with('success', __('exams.status_updated'));
    }

    public function destroy($id)
    {
        $exam = Exam::findOrFail($id);
        
        if ($exam->banner) {
            $publicBanner = public_path('storage/' . $exam->banner);
            if (file_exists($publicBanner)) {
                @unlink($publicBanner);
            }
            if (Storage::disk('public')->exists($exam->banner)) {
                Storage::disk('public')->delete($exam->banner);
            }
        }
        
        $exam->delete();

        return redirect()->back()->with('success', __('exams.delete_success'));
    }

    public function bulkDestroy(Request $request)
    {
        $request->validate([
            'ids'   => 'required|array',
            'ids.*' => 'exists:exams,id',
        ]);

        $exams = Exam::whereIn('id', $request->ids)->get();

        foreach ($exams as $exam) {
            if ($exam->banner) {
                $publicBanner = public_path('storage/' . $exam->banner);
                if (file_exists($publicBanner)) {
                    @unlink($publicBanner);
                }
                if (Storage::disk('public')->exists($exam->banner)) {
                    Storage::disk('public')->delete($exam->banner);
                }
            }
            $exam->delete();
        }

        return redirect()->back()->with('success', __('exams.bulk_delete_success', ['count' => count($request->ids)]));
    }

    public function questions($id)
    {
        $exam = Exam::with('questions')->findOrFail($id);
        return redirect()->back()->with('info', __('exams.manage_questions', ['title' => $exam->title]));
    }
}