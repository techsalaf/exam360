<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\StudentExamSession;
use App\Models\StudentExamAnswer;
use App\Models\SystemSetting;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Carbon;

class ResultController extends Controller
{
    public function index(Request $request)
    {
        $userId = Auth::id();

        $results = StudentExamSession::where('user_id', $userId)
            ->where('status', 'completed')
            ->with(['exam:id,title,pass_percentage,is_paid,result_date'])
            ->latest()
            ->paginate(10);
        
        return view('user.results.index', compact('results'));
    }

    public function show($examId)
    {
        $userId = Auth::id();

        $session = StudentExamSession::where('user_id', $userId)
            ->where(function($q) use ($examId) {
                $q->where('exam_id', $examId)->orWhere('id', $examId);
            })
            ->where('status', 'completed')
            ->with(['exam.questions', 'user', 'answers'])
            ->latest('created_at')
            ->firstOrFail();

        $this->calculateAndSaveScore($session);
        $session->refresh(); 

        $isPublished = true;
        
        if ($session->exam->is_paid) {
            $resultDate = $session->exam->result_date ? Carbon::parse($session->exam->result_date) : null;
            if (!$resultDate || $resultDate->isFuture()) {
                $isPublished = false;
            }
        }

        if (!$isPublished) {
            return view('user.results.pending', compact('session'));
        }
        
        $totalQuestions = $session->exam->questions->count();
        $examTotalMarks = $session->exam->total_marks > 0 ? $session->exam->total_marks : $totalQuestions;
        
        $maxPossibleScore = $session->exam->questions->sum('mark');
        $maxPossibleScore = $maxPossibleScore > 0 ? $maxPossibleScore : $examTotalMarks;

        $answersKeyed = $session->answers->keyBy('question_id');
        
        $correctCount = 0;
        $wrongCount = 0;
        $skippedCount = 0;
        $underReviewCount = 0;

        $questionBreakdown = $session->exam->questions->map(function($question) use ($answersKeyed, &$correctCount, &$wrongCount, &$skippedCount, &$underReviewCount) {
            $ans = $answersKeyed->get($question->id);
            $status = 'skipped';
            
            if ($ans && $ans->selected_option_id !== null && $ans->selected_option_id !== '') {
                if ($question->type === 'coding') {
                    if ($ans->marks_awarded > 0) {
                        $status = 'correct';
                    } elseif ($ans->marks_awarded < 0) {
                        $status = 'wrong';
                    } else {
                        $status = 'under_review';
                        $underReviewCount++;
                    }
                } else {
                    $status = $ans->is_correct ? 'correct' : 'wrong';
                }
            }

            if ($status === 'correct') {
                $correctCount++;
            } elseif ($status === 'wrong') {
                $wrongCount++;
            } elseif ($status === 'skipped') {
                $skippedCount++;
            }

            return (object) [
                'id' => $question->id,
                'type' => $question->type,
                'text' => $question->question_text ?? 'N/A',
                'status' => $status,
                'selected_option' => ($ans && $status !== 'skipped') ? $this->getOptionText($question, $ans->selected_option_id) : 'Skipped',
                'correct_option' => $question->type === 'coding' ? 'Manual Admin Review' : $this->getOptionText($question, $question->correct_answer),
                'explanation' => $question->explanation
            ];
        });

        $incorrectCount = $totalQuestions - $correctCount - $underReviewCount - $skippedCount;

        $grossScoreObtained = $session->answers->where('is_correct', true)->sum('marks_awarded');
        $deductedMarks = max(0, $grossScoreObtained - $session->score);

        $result = (object) [
            'id' => $session->id,
            'exam_id' => $session->exam_id,
            'exam' => $session->exam,
            'percentage' => $session->progress_percentage,
            'score_obtained' => $session->score,
            'total_marks' => $maxPossibleScore,
            'is_passed' => $session->progress_percentage >= ($session->exam->pass_percentage ?? 50),
            'correct_count' => $correctCount,
            'incorrect_count' => $incorrectCount,
            'skipped_count' => $skippedCount,
            'under_review_count' => $underReviewCount,
            'deducted_marks' => $deductedMarks,
            'total_questions' => $totalQuestions,
            'total_time_taken' => $session->end_time && $session->start_time 
                ? $session->end_time->diffInMinutes($session->start_time) 
                : 0,
            'created_at' => $session->created_at,
            'user' => $session->user
        ];

        return view('user.results.show', compact('result', 'questionBreakdown'));
    }
    
    public function certificates()
    {
        $userId = Auth::id();

        $allSessions = StudentExamSession::where('user_id', $userId)
            ->where('status', 'completed')
            ->with('exam')
            ->orderBy('created_at', 'desc')
            ->get();

        $groupedSessions = $allSessions->groupBy('exam_id');

        $earnedCertificates = collect();
        $pendingCertificates = collect();
        $lockedCertificates = collect();

        foreach ($groupedSessions as $examId => $sessions) {
            $issuedSession = $sessions->firstWhere('certificate_issued_at', '!=', null);
            
            if ($issuedSession) {
                $earnedCertificates->push($issuedSession);
                continue; 
            }

            $passedSession = $sessions->first(function ($session) {
                $passMark = $session->exam->pass_percentage ?? 50;
                return $session->progress_percentage >= $passMark;
            });

            if ($passedSession) {
                $pendingCertificates->push($passedSession);
                continue; 
            }

            $bestFailedSession = $sessions->sortByDesc('progress_percentage')->first();
            
            if ($bestFailedSession) {
                $lockedCertificates->push($bestFailedSession);
            }
        }

        return view('user.certificates.index', compact('earnedCertificates', 'pendingCertificates', 'lockedCertificates'));
    }

    public function downloadCertificate($id)
    {
        $session = StudentExamSession::where('user_id', Auth::id())
            ->with(['user', 'exam'])
            ->findOrFail($id);
        
        $passMark = $session->exam->pass_percentage ?? 50;
        $isPassed = $session->progress_percentage >= $passMark;

        if (!$isPassed || !$session->certificate_issued_at) {
            return redirect()->back()->with('error', __('frontend.certificate_not_available'));
        }

        $settings = SystemSetting::pluck('value', 'key')->toArray();

        $encodeImage = function($key) use ($settings) {
            if (empty($settings[$key])) {
                return null;
            }
            
            $path = public_path('storage/' . str_replace('storage/', '', $settings[$key]));
            
            return File::exists($path) 
                ? 'data:' . File::mimeType($path) . ';base64,' . base64_encode(File::get($path)) 
                : null;
        };

        $result = (object) [
            'id' => $session->id,
            'user' => $session->user,
            'exam' => $session->exam,
            'percentage' => $session->progress_percentage,
            'created_at' => $session->created_at,
            'grade' => $isPassed ? 'PASS' : 'FAIL'
        ];

        $data = [
            'result' => $result,
            'settings' => $settings,
            'logo_image' => $encodeImage('app_logo_light'),
            'sig_image' => $encodeImage('cert_sig_image'),
            'bg_image' => $encodeImage('cert_bg_image'),
            'app_name' => config('app.name', 'ZiExam AI'),
            'parsed_body' => $this->parseCertificateBody($settings['cert_body'] ?? '', $result)
        ];

        $pdf = Pdf::loadView('admin.exams.results.certificate_pdf', $data);
        $pdf->setPaper('a4', $settings['cert_orientation'] ?? 'landscape');
        
        return $pdf->download('Certificate_' . $session->exam->title . '.pdf');
    }

    private function parseCertificateBody($template, $result)
    {
        $replacements = [
            '{{full_name}}'    => $result->user->name,
            '{{exam_title}}'   => $result->exam->title,
            '{{score}}'        => round($result->percentage) . '%',
            '{{completed_at}}' => $result->created_at->format('F d, Y'),
        ];
        
        $template = str_replace('@{{', '{{', $template);
        
        foreach ($replacements as $key => $value) {
            $template = str_replace($key, $value, $template);
        }
        
        return $template;
    }
    
    protected function calculateAndSaveScore(StudentExamSession $session)
    {
        $session->loadMissing(['answers.question', 'exam.questions']);
        
        $correctCount = 0;
        $totalAnswered = 0;
        $netScore = 0;
        
        $hasNegativeMarking = (bool)$session->exam->has_negative_marking;
        $negativeValue = (float)($session->exam->negative_mark_value ?? 0.0);
        
        $totalQuestions = $session->exam->questions->count();

        if ($totalQuestions === 0) {
            $session->update([
                'score' => 0, 
                'progress_percentage' => 0,
                'correct_answers' => 0,
                'total_answered' => 0
            ]);
            return;
        }

        $maxPossibleScore = $session->exam->questions->sum('mark');
        $maxPossibleScore = $maxPossibleScore > 0 ? $maxPossibleScore : $totalQuestions;
        $marksPerQuestion = $maxPossibleScore / $totalQuestions; 

        foreach ($session->answers as $answer) {
            $question = $answer->question;
            
            if (!$question) {
                continue;
            }
            
            if ($answer->selected_option_id !== null && $answer->selected_option_id !== '') {
                $totalAnswered++;
                
                $questionMark = $question->mark > 0 ? $question->mark : $marksPerQuestion;
                
                $isCorrect = false;
                $marksAwarded = 0;

                if ($question->type === 'coding') {
                    if ($answer->marks_awarded > 0) {
                        $isCorrect = true;
                        $marksAwarded = $answer->marks_awarded;
                        $correctCount++;
                    } elseif ($answer->marks_awarded < 0) {
                        $isCorrect = false;
                        $marksAwarded = $answer->marks_awarded;
                    }
                } else {
                    $isCorrect = (trim((string)$answer->selected_option_id) === trim((string)$question->correct_answer));
                    if ($isCorrect) {
                        $marksAwarded = $questionMark;
                        $correctCount++;
                    } elseif ($hasNegativeMarking) {
                        $marksAwarded = -abs($negativeValue);
                    }

                    StudentExamAnswer::where('id', $answer->id)->update([
                        'is_correct' => $isCorrect, 
                        'marks_awarded' => $marksAwarded
                    ]);
                }

                $netScore += ($marksAwarded > 0 ? $marksAwarded : 0);
            }
        }
        
        $netScore = max(0, $netScore);
        
        $percentage = ($maxPossibleScore > 0) ? round(($netScore / $maxPossibleScore) * 100) : 0;

        $session->update([
            'correct_answers' => $correctCount,
            'total_answered' => $totalAnswered,
            'score' => $netScore, 
            'progress_percentage' => $percentage, 
        ]);
    }

    private function getOptionText($question, $optionId)
    {
        if (!$question || $optionId === null || $optionId === '') {
            return null;
        }

        if ($question->type === 'coding') {
            return $optionId;
        }
        
        $options = $question->options;
        if (is_string($options)) {
            $options = json_decode($options, true);
        }
        
        if (is_array($options)) {
            if (isset($options[$optionId])) {
                $opt = $options[$optionId];
                return is_array($opt) ? ($opt['option_text'] ?? ($opt['text'] ?? null)) : $opt;
            }

            foreach ($options as $key => $opt) {
                if (is_array($opt) && isset($opt['id']) && (string)$opt['id'] === (string)$optionId) {
                    return $opt['option_text'] ?? ($opt['text'] ?? null);
                }
                
                if ((string)$key === (string)$optionId) {
                    return is_array($opt) ? ($opt['option_text'] ?? ($opt['text'] ?? null)) : $opt;
                }
            }
        }
        
        return $optionId;
    }
}