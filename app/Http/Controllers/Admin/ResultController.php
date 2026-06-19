<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StudentExamSession;
use App\Models\StudentExamAnswer;
use App\Models\SystemSetting;
use App\Services\AI\AIResultAnalyzer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ResultController extends Controller
{
    public function index(Request $request) 
    {
        $query = StudentExamSession::where('status', 'completed')
            ->where(function($q) {
                $q->whereHas('exam', function($e) {
                    $e->where('is_paid', false)
                      ->orWhere('result_date', '<=', now());
                });
            })
            ->with(['user', 'exam']);

        if ($request->filled('search')) {
            $term = $request->search;
            $query->where(function($q) use ($term) {
                $q->whereHas('user', function($u) use ($term) {
                    $u->where('name', 'like', "%{$term}%")
                      ->orWhere('email', 'like', "%{$term}%");
                })->orWhereHas('exam', function($e) use ($term) {
                    $e->where('title', 'like', "%{$term}%");
                });
            });
        }

        $results = $query->latest('updated_at')->paginate(10);

        $allDeclared = $query->get(); 
        $total = $allDeclared->count();
        $passed = $allDeclared->filter(fn($s) => $s->is_passed)->count();
        $failed = $total - $passed;
        $rate = $total > 0 ? round(($passed / $total) * 100) : 0;

        $kpis = [
            ['title' => 'Total Declared', 'value' => number_format($total), 'icon' => 'fa-solid fa-file-signature', 'color' => 'primary', 'trend' => 'Published'],
            ['title' => 'Pass Rate', 'value' => $rate . '%', 'icon' => 'fa-solid fa-chart-pie', 'color' => 'success', 'trend' => 'Performance'],
            ['title' => 'Passed', 'value' => number_format($passed), 'icon' => 'fa-solid fa-user-graduate', 'color' => 'success', 'trend' => 'Qualified'],
            ['title' => 'Failed', 'value' => number_format($failed), 'icon' => 'fa-solid fa-circle-exclamation', 'color' => 'danger', 'trend' => 'Needs Help']
        ];

        return view('admin.exams.results.index', compact('results', 'kpis'));
    }

    public function pending(Request $request)
    {
        $query = StudentExamSession::where('status', 'completed')
            ->whereHas('exam', function($q) {
                $q->where('is_paid', true)
                  ->where(function($sub) {
                      $sub->where('result_date', '>', now())
                          ->orWhereNull('result_date');
                  });
            })
            ->with(['user', 'exam']);

        if ($request->filled('search')) {
            $term = $request->search;
            $query->whereHas('user', function($u) use ($term) {
                $u->where('name', 'like', "%{$term}%");
            });
        }

        $results = $query->latest('updated_at')->paginate(10);

        return view('admin.exams.results.pending', compact('results'));
    }

    public function publish($id)
    {
        $session = StudentExamSession::with('exam')->findOrFail($id);
        $session->exam->update(['result_date' => now()->subMinute()]);
        return redirect()->back()->with('success', 'Exam results published successfully.');
    }

    public function show($id) 
    {
        $result = StudentExamSession::where('status', 'completed')
            ->with(['user', 'exam.questions', 'answers.question'])
            ->findOrFail($id);
            
        $questions = $result->exam->questions;

        return view('admin.exams.results.show', compact('result', 'questions'));
    }

    public function markAnswer(Request $request, $answerId)
    {
        $answer = StudentExamAnswer::with(['question', 'session.exam'])->findOrFail($answerId);
        $session = $answer->session;
        
        $isCorrect = $request->status === 'correct';
        $questionMark = floatval($answer->question->mark ?? 1);

        $answer->update([
            'is_correct' => $isCorrect,
            'marks_awarded' => $isCorrect ? $questionMark : -0.01
        ]);

        $allAnswers = StudentExamAnswer::where('student_exam_session_id', $session->id)->get();
        $correctCount = $allAnswers->where('is_correct', true)->count();
        $totalObtainedScore = $allAnswers->sum(function($a) {
            return $a->marks_awarded > 0 ? $a->marks_awarded : 0;
        });
        
        $totalPossibleMarks = $session->exam->questions->sum('mark');
        if($totalPossibleMarks <= 0) $totalPossibleMarks = $session->exam->questions->count();
        
        $percentage = $totalPossibleMarks > 0 ? ($totalObtainedScore / $totalPossibleMarks) * 100 : 0;
        $passPercentage = $session->exam->pass_percentage ?? 40;
        $isPassed = $percentage >= $passPercentage;

        $session->update([
            'correct_answers' => $correctCount,
            'score' => $totalObtainedScore,
            'progress_percentage' => round($percentage, 2),
            'is_passed' => $isPassed
        ]);

        return redirect()->back()->with('success', 'Grading updated and results recalculated.');
    }

    public function analyze(Request $request, int $id, AIResultAnalyzer $analyzer) 
    {
        try {
            $result = StudentExamSession::with(['user', 'exam', 'answers'])->findOrFail($id);
            $analysis = $analyzer->analyze($result);
            return response()->json($analysis);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Analysis failed.'], 500);
        }
    }

    public function issueCertificate($id) 
    {
        $result = StudentExamSession::findOrFail($id);
        if (!$result->is_passed) {
            return redirect()->back()->with('error', 'Only passed exams can receive certificates.');
        }
        $result->certificate_issued_at = now();
        $result->save();
        return redirect()->back()->with('success', 'Certificate issued.');
    }

    public function destroy($id) 
    {
        try {
            $result = StudentExamSession::findOrFail($id);
            $result->delete();
            return redirect()->back()->with('success', 'Record deleted.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Delete failed.');
        }
    }

    public function downloadCertificate($id)
    {
        $result = StudentExamSession::with(['user', 'exam'])->findOrFail($id);
        if (!$result->is_passed) {
            return redirect()->back()->with('error', 'Certificate unavailable.');
        }
        if (!$result->certificate_issued_at) {
            $result->update(['certificate_issued_at' => now()]);
        }
        $settings = SystemSetting::getSettings();
        $encodeImage = function($key) use ($settings) {
            if (empty($settings[$key])) return null;
            $filename = str_replace('storage/', '', $settings[$key]);
            $path = public_path('storage/' . $filename);
            if (File::exists($path)) {
                return 'data:' . File::mimeType($path) . ';base64,' . base64_encode(File::get($path));
            }
            return null;
        };
        $data = [
            'result' => $result,
            'settings' => $settings,
            'sig_image' => $encodeImage('cert_sig_image'),
            'logo_image' => $encodeImage('app_logo_light'),
            'bg_image' => $encodeImage('cert_bg_image'),
            'app_name' => config('app.name', 'ZiExam AI'),
            'parsed_body' => $this->parseCertificateBody($settings['cert_body'] ?? '', $result)
        ];
        $pdf = Pdf::loadView('admin.exams.results.certificate_pdf', $data);
        $pdf->setPaper('a4', $settings['cert_orientation'] ?? 'landscape');
        return $pdf->download('Certificate_' . $result->user->name . '.pdf');
    }
    
    public function downloadPdf($id) 
    {
        $session = StudentExamSession::where('status', 'completed')
            ->with(['user', 'exam.category', 'answers.question'])
            ->findOrFail($id);
        $settings = SystemSetting::getSettings();
        $encodeImage = function($key) use ($settings) {
            if (empty($settings[$key])) return null;
            $filename = str_replace('storage/', '', $settings[$key]);
            $path = public_path('storage/' . $filename);
            if (File::exists($path)) return 'data:' . File::mimeType($path) . ';base64,' . base64_encode(File::get($path));
            return null;
        };
        $logoBase64 = $encodeImage('app_logo_light'); 
        $isPassed = $session->is_passed;
        $gradeText = $isPassed ? 'PASS' : 'FAIL';
        $totalPossibleScore = $session->exam->questions->sum('mark');
        $result = (object) [
            'id' => $session->id,
            'user' => $session->user,
            'exam' => $session->exam,
            'percentage' => $session->progress_percentage,
            'is_passed' => $isPassed,
            'grade' => $gradeText, 
            'correct_answers' => $session->correct_answers,
            'total_questions' => $session->total_questions,
            'score' => $session->score,
            'total_score' => $totalPossibleScore, 
            'created_at' => $session->created_at,
            'start_time' => $session->start_time,
            'end_time' => $session->end_time,
            'answers' => $session->answers 
        ];
        $questions = $session->exam->questions;
        $data = compact('result', 'questions', 'settings');
        $data['logoBase64'] = $logoBase64;
        $pdf = Pdf::loadView('admin.exams.results.pdf', $data);
        $pdf->setPaper('a4', 'portrait');
        $pdf->setOptions(['dpi' => 150, 'defaultFont' => 'sans-serif', 'isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true]);
        $filename = 'Result_' . $session->user->name . '_' . $session->exam->title . '_' . $session->id . '.pdf';
        return $pdf->download($filename);
    }

    private function parseCertificateBody($template, $result)
    {
        $replacements = [
            '{{full_name}}'    => $result->user->name,
            '{{exam_title}}'   => $result->exam->title,
            '{{score}}'        => round($result->percentage) . '%',
            '{{completed_at}}' => $result->created_at->format('F d, Y'),
            '{{grade}}'        => $result->grade,
        ];
        $template = str_replace('@{{', '{{', $template);
        foreach ($replacements as $key => $value) {
            $template = str_replace($key, $value, $template);
        }
        return $template;
    }

    public function export(Request $request)
    {
        $query = StudentExamSession::where('status', 'completed')
            ->with(['user', 'exam.category']);

        if ($request->filled('search')) {
            $term = $request->search;
            $query->where(function($q) use ($term) {
                $q->whereHas('user', function($u) use ($term) {
                    $u->where('name', 'like', "%{$term}%")->orWhere('email', 'like', "%{$term}%");
                })->orWhereHas('exam', function($e) use ($term) {
                    $e->where('title', 'like', "%{$term}%");
                });
            });
        }

        if ($request->filled('start_date')) {
            $query->whereDate('updated_at', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('updated_at', '<=', $request->end_date);
        }

        $results = $query->latest('updated_at')->get();

        if ($request->format === 'csv') {
            $fileName = 'student_results_' . now()->format('YmdHis') . '.csv';
            $headers = [
                "Content-type"        => "text/csv",
                "Content-Disposition" => "attachment; filename=$fileName",
                "Pragma"              => "no-cache",
                "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
                "Expires"             => "0"
            ];

            $columns = ['Student Name', 'Email', 'Exam Title', 'Category', 'Score (%)', 'Correct', 'Total Questions', 'Status', 'Date'];

            $callback = function() use($results, $columns) {
                $file = fopen('php://output', 'w');
                fputcsv($file, $columns);

                foreach ($results as $r) {
                    fputcsv($file, [
                        $r->user->name,
                        $r->user->email,
                        $r->exam->title,
                        $r->exam->category->name ?? 'N/A',
                        $r->progress_percentage . '%',
                        $r->correct_answers,
                        $r->total_questions,
                        $r->is_passed ? 'PASSED' : 'FAILED',
                        $r->updated_at->format('M d, Y')
                    ]);
                }
                fclose($file);
            };

            return response()->stream($callback, 200, $headers);
        }

        $settings = SystemSetting::getSettings();
        $pdf = Pdf::loadView('admin.exams.results.export_pdf', compact('results', 'settings'));
        $pdf->setPaper('a4', 'landscape');
        return $pdf->download('results_report_' . now()->format('YmdHis') . '.pdf');
    }
}