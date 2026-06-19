<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\Question;
use App\Services\AI\QuestionGeneratorService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class QuestionController extends Controller
{
    public function index($examId)
    {
        $exam = Exam::with(['category'])->withCount('questions')->findOrFail($examId);
        
        $questions = Question::where('exam_id', $examId)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $codingAssessments = class_exists('\Addons\CodingAssessments\Models\CodingAssessment') 
            ? \Addons\CodingAssessments\Models\CodingAssessment::all() 
            : collect();

        return view('admin.exams.questions.index', compact('exam', 'questions', 'codingAssessments'));
    }

    public function generate(Request $request, QuestionGeneratorService $aiService, $examId)
    {
        $request->validate([
            'topic'      => 'required|string|max:255',
            'count'      => 'required|integer|min:1|max:50',
            'difficulty' => 'required|in:easy,normal,hard',
            'type'       => 'required|in:mcq,true_false',
            'provider'   => 'required|in:custom,gemini',
        ]);
        
        $result = $aiService->generate(
            $request->topic,
            $request->count,
            $request->difficulty,
            $request->type,
            $request->provider
        );

        if ($result['status'] === 'error') {
            return response()->json(['status' => 'error', 'message' => $result['message']], 422);
        }

        return response()->json([
            'status' => 'success',
            'questions' => $result['questions']
        ]);
    }
    
    public function store(Request $request, $examId) 
    {
        if ($request->has('questions') && is_array($request->questions)) {
             return $this->storeBatch($request, $examId);
        }

        $validator = Validator::make($request->all(), [
            'exam_id' => 'required|integer|in:' . $examId,
            'question_text' => 'required|string',
            'type' => 'required|in:mcq,true_false,short_answer,coding',
            'correct_answer' => 'required_unless:type,coding|string|max:255',
            'options' => 'nullable|array',
            'explanation' => 'nullable|string',
            'allowed_languages' => 'required_if:type,coding|array',
            'test_cases' => 'required_if:type,coding|array'
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'errors' => $validator->errors()], 422);
        }
        
        $isMcq = $request->type === 'mcq';
        $isCoding = $request->type === 'coding';

        $options = null;
        if ($isMcq) {
            $options = $request->options;
        } elseif ($isCoding) {
            $options = [
                'allowed_languages' => $request->allowed_languages,
                'test_cases' => $request->test_cases
            ];
        }

        $question = Question::create([
            'exam_id'        => $examId,
            'question_text'  => $request->question_text,
            'type'           => $request->type,
            'correct_answer' => $isCoding ? 'coding_submission' : $request->correct_answer,
            'explanation'    => $request->explanation,
            'options'        => $options, 
        ]);

        return response()->json([
            'status' => 'success', 
            'message' => __('questions.save_success'), 
            'question_id' => $question->id
        ]);
    }

    public function import(Request $request, $examId)
    {
        try {
            $validator = Validator::make($request->all(), [
                'file' => 'required|file|max:20480'
            ]);

            if ($validator->fails()) {
                return response()->json(['status' => 'error', 'message' => $validator->errors()->first('file')]);
            }

            $extension = strtolower($request->file('file')->getClientOriginalExtension());
            if (!in_array($extension, ['csv', 'txt'])) {
                return response()->json(['status' => 'error', 'message' => 'Only CSV or TXT files are allowed.']);
            }

            set_time_limit(0);
            ini_set('memory_limit', '512M');

            $filePath = $request->file('file')->getRealPath();
            $handle = fopen($filePath, "r");
            
            if (!$handle) {
                return response()->json(['status' => 'error', 'message' => 'Failed to open the uploaded file.']);
            }

            $header = fgetcsv($handle, 10000, ",");
            
            if (!$header || empty(array_filter($header))) {
                fclose($handle);
                return response()->json(['status' => 'error', 'message' => 'Invalid or empty CSV format.']);
            }

            $header[0] = preg_replace('/[\xef\xbb\xbf]/', '', $header[0]);
            $header = array_map('trim', $header);
            $header = array_map('strtolower', $header);

            $required = ['question_text', 'type', 'correct_answer'];
            foreach ($required as $req) {
                if (!in_array($req, $header)) {
                    fclose($handle);
                    return response()->json(['status' => 'error', 'message' => "CSV missing required column: {$req}"]);
                }
            }

            $chunkSize = 500;
            $questions = [];
            $totalImported = 0;
            $now = now();

            DB::beginTransaction();

            while (($data = fgetcsv($handle, 10000, ",")) !== FALSE) {
                if (empty(array_filter($data))) continue;

                if (count($data) < count($header)) {
                    $data = array_pad($data, count($header), '');
                } elseif (count($data) > count($header)) {
                    $data = array_slice($data, 0, count($header));
                }

                $row = array_combine($header, $data);
                
                $type = strtolower(trim($row['type'] ?? 'mcq'));
                if (!in_array($type, ['mcq', 'true_false', 'short_answer'])) {
                    $type = 'mcq';
                }

                $options = null;
                if ($type === 'mcq' && !empty($row['options'])) {
                    $rawOptions = explode('|', $row['options']);
                    $formattedOptions = [];
                    $letters = ['A', 'B', 'C', 'D', 'E', 'F', 'G'];
                    foreach ($rawOptions as $index => $val) {
                        if (isset($letters[$index]) && trim($val) !== '') {
                            $formattedOptions[$letters[$index]] = trim($val);
                        }
                    }
                    $options = json_encode($formattedOptions);
                }

                $questions[] = [
                    'exam_id'        => $examId,
                    'question_text'  => $row['question_text'] ?? 'Untitled Question',
                    'type'           => $type,
                    'correct_answer' => trim($row['correct_answer'] ?? ''),
                    'explanation'    => $row['explanation'] ?? null,
                    'options'        => $options,
                    'created_at'     => $now,
                    'updated_at'     => $now,
                ];

                if (count($questions) >= $chunkSize) {
                    Question::insert($questions);
                    $totalImported += count($questions);
                    $questions = [];
                }
            }

            if (count($questions) > 0) {
                Question::insert($questions);
                $totalImported += count($questions);
            }

            DB::commit();
            fclose($handle);

            return response()->json([
                'status' => 'success', 
                'message' => $totalImported . ' questions imported successfully.'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            if (isset($handle) && is_resource($handle)) {
                fclose($handle);
            }
            return response()->json([
                'status' => 'error', 
                'message' => 'System Error: ' . $e->getMessage() . ' at line ' . $e->getLine()
            ]);
        }
    }

    public function downloadTemplate()
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="question_template.csv"',
        ];

        $columns = ['question_text', 'type', 'correct_answer', 'options', 'explanation'];

        $callback = function() use ($columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            fputcsv($file, [
                'What is the capital of France?',
                'mcq',
                'A',
                'Paris|London|Berlin|Madrid',
                'Paris is the capital and most populous city of France.'
            ]);
            fputcsv($file, [
                'Laravel is a PHP framework.',
                'true_false',
                'True',
                '',
                'Laravel is indeed a popular PHP web framework.'
            ]);
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function update(Request $request, $examId, $questionId) 
    {
        $request->validate([
            'question_text' => 'required|string',
            'type' => 'required|in:mcq,true_false,short_answer,coding',
            'correct_answer' => 'required_unless:type,coding|string|max:255',
            'options' => 'nullable|array',
            'explanation' => 'nullable|string',
            'allowed_languages' => 'required_if:type,coding|array',
            'test_cases' => 'required_if:type,coding|array'
        ]);
        
        $question = Question::where('exam_id', $examId)->findOrFail($questionId);
        
        $isMcq = $request->type === 'mcq';
        $isCoding = $request->type === 'coding';

        $options = null;
        if ($isMcq) {
            $options = $request->options;
        } elseif ($isCoding) {
            $options = [
                'allowed_languages' => $request->allowed_languages,
                'test_cases' => $request->test_cases
            ];
        }

        $question->update([
            'question_text'  => $request->question_text,
            'type'           => $request->type,
            'correct_answer' => $isCoding ? 'coding_submission' : $request->correct_answer,
            'explanation'    => $request->explanation,
            'options'        => $options, 
        ]);
        
        return response()->json(['status' => 'success', 'message' => __('questions.update_success')]);
    }

    protected function storeBatch(Request $request, $examId) 
    {
        $validator = Validator::make($request->all(), [
            'questions' => 'required|array|min:1',
            'questions.*.question_text' => 'required|string',
            'questions.*.type' => 'required|in:mcq,true_false,short_answer,coding',
            'questions.*.correct_answer' => 'required_unless:questions.*.type,coding|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'errors' => $validator->errors()], 422);
        }

        $chunkSize = 500;
        $questionsToInsert = [];
        $now = now();

        DB::beginTransaction();
        try {
            foreach ($request->questions as $q) {
                $isMcq = isset($q['type']) && $q['type'] === 'mcq';
                $isCoding = isset($q['type']) && $q['type'] === 'coding';
                
                $options = null;
                if ($isMcq && isset($q['options'])) {
                    $options = json_encode($q['options']);
                } elseif ($isCoding && isset($q['coding_data'])) {
                    $options = json_encode($q['coding_data']);
                }

                $questionsToInsert[] = [
                    'exam_id'        => $examId,
                    'question_text'  => $q['question_text'],
                    'type'           => $q['type'],
                    'options'        => $options, 
                    'correct_answer' => $isCoding ? 'coding_submission' : $q['correct_answer'],
                    'explanation'    => $q['explanation'] ?? null,
                    'created_at'     => $now,
                    'updated_at'     => $now,
                ];

                if (count($questionsToInsert) >= $chunkSize) {
                    Question::insert($questionsToInsert);
                    $questionsToInsert = [];
                }
            }

            if (count($questionsToInsert) > 0) {
                Question::insert($questionsToInsert);
            }
            DB::commit();
            return response()->json(['status' => 'success', 'message' => __('questions.save_success')]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }
    
    public function destroy($examId, $questionId) 
    {
        $question = Question::where('exam_id', $examId)->findOrFail($questionId);
        $question->delete();
        return redirect()->back()->with('success', __('questions.delete_success'));
    }
}