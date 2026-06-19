<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Exam;
use App\Models\StudentExamSession;
use App\Models\StudentExamAnswer;
use Addons\CodingAssessments\Models\CodingSubmission;
use Addons\CodingAssessments\Models\CodingAssessment;

class ExamController extends Controller
{
    public function myExams()
    {
        $user = Auth::user();

        $purchasedExams = DB::table('exam_user')
            ->where('user_id', $user->id)
            ->get()
            ->keyBy('exam_id');

        $planIncludedExamIds = [];
        if ($user->is_subscribed && $user->plan) {
            $categoryIds = $user->plan->categories()->pluck('categories.id')->toArray();
            $planIncludedExamIds = Exam::where(function($query) use ($user, $categoryIds) {
                $query->whereIn('category_id', $categoryIds)
                      ->orWhere('plan_id', $user->plan_id);
            })->pluck('id')->toArray();
        }

        $attemptedExamIds = StudentExamSession::where('user_id', $user->id)
            ->pluck('exam_id')
            ->toArray();

        $allExamIds = array_unique(array_merge(
            $purchasedExams->keys()->toArray(),
            $planIncludedExamIds,
            $attemptedExamIds
        ));

        if (empty($allExamIds)) {
            return view('user.exams.my-exams', [
                'exams' => [
                    'available' => collect(),
                    'ongoing'   => collect(),
                    'completed' => collect(),
                    'upcoming'  => collect()
                ]
            ]);
        }

        $allExams = Exam::whereIn('id', $allExamIds)
            ->with(['category'])
            ->withCount('questions')
            ->get();

        $sessions = StudentExamSession::where('user_id', $user->id)
            ->whereIn('exam_id', $allExamIds)
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy('exam_id');

        $exams = [
            'available' => collect(),
            'ongoing'   => collect(),
            'completed' => collect(),
            'upcoming'  => collect()
        ];

        foreach ($allExams as $exam) {
            $pivotData = $purchasedExams->get($exam->id);
            $accessStatus = $pivotData->status ?? 'active';
            $exam->setRelation('pivot', (object)['status' => $accessStatus]);

            $userSessions = $sessions->get($exam->id);
            $latestSession = $userSessions ? $userSessions->first() : null;
            
            $exam->user_session = $latestSession;
            $exam->progress = $latestSession ? ($latestSession->progress_percentage ?? 0) : 0;

            if ($latestSession) {
                if ($latestSession->status === 'completed') {
                    $exams['completed']->push($exam);
                    continue;
                }

                if ($latestSession->status === 'ongoing') {
                    if ($latestSession->end_time && Carbon::now()->gt($latestSession->end_time)) {
                        $exams['completed']->push($exam);
                    } else {
                        $exams['ongoing']->push($exam);
                    }
                    continue;
                }
            }

            if ($accessStatus === 'completed' || $accessStatus === 'expired') {
                $exams['completed']->push($exam);
                continue;
            }

            if ($accessStatus === 'pending') {
                $exams['available']->push($exam);
                continue;
            }

            if ($exam->start_date && Carbon::parse($exam->start_date)->isFuture()) {
                $exams['upcoming']->push($exam);
                continue;
            }

            $exams['available']->push($exam);
        }

        $exams['available'] = $exams['available']->sortByDesc('created_at');
        $exams['completed'] = $exams['completed']->sortByDesc(function ($exam) {
            return $exam->user_session->updated_at ?? $exam->id;
        });

        return view('user.exams.my-exams', ['exams' => $exams]);
    }

    public function participate(Exam $exam)
    {
        $user = Auth::user();

        if (!$exam->is_active) {
            $hasCompleted = StudentExamSession::where('user_id', $user->id)
                ->where('exam_id', $exam->id)
                ->where('status', 'completed')
                ->exists();

            if (!$hasCompleted) {
                return redirect()->route('my.exams')->with('error', __('frontend.exam_inactive'));
            }
        }

        if (!$user->canAccessExam($exam)) {
            $sessionExists = StudentExamSession::where('user_id', $user->id)->where('exam_id', $exam->id)->exists();
            if (!$sessionExists) {
                return redirect()->route('exams.list')->with('error', __('frontend.purchase_required'));
            }
        }

        $lastSession = StudentExamSession::where('user_id', $user->id)
            ->where('exam_id', $exam->id)
            ->latest('created_at')
            ->first();

        $isUpcoming = (!$lastSession && $exam->start_date && Carbon::now()->lt($exam->start_date));
        $session = null;
        $secondsRemaining = 0;

        if ($lastSession && $lastSession->status === 'ongoing') {
            if ($lastSession->end_time && Carbon::now()->lt($lastSession->end_time)) {
                $session = $lastSession;
                $secondsRemaining = Carbon::now()->diffInSeconds($lastSession->end_time, false);
                $lastSession->update(['last_activity_at' => Carbon::now()]);
            } else {
                $this->finalizeSession($lastSession);
            }
        }

        if ($lastSession && $lastSession->status === 'completed' && $exam->is_paid && !$exam->allow_retake) {
            return redirect()->route('user.results.show', $lastSession->id);
        }

        $existingAnswers = [];
        $questionsList = collect([]);

        if ($session) {
            $questionsList = $this->prepareQuestions($exam, $session->id);
            $existingAnswers = StudentExamAnswer::where('student_exam_session_id', $session->id)
                ->pluck('selected_option_id', 'question_id')
                ->map(fn($val) => (string)$val)
                ->toArray();
        }

        return view('user.exams.participate', [
            'exam' => $exam,
            'session' => $session,
            'lastSession' => $lastSession,
            'questions' => $questionsList, 
            'existingAnswers' => $existingAnswers,
            'secondsRemaining' => $secondsRemaining,
            'isUpcoming' => $isUpcoming 
        ]);
    }

    public function startExam(Request $request, Exam $exam)
    {
        $user = Auth::user();

        if ($exam->start_date && Carbon::now()->lt($exam->start_date)) {
            return redirect()->back()->with('error', __('frontend.exam_not_started'));
        }

        if (!$user->canAccessExam($exam)) {
            return redirect()->route('exams.list');
        }

        $activeSession = StudentExamSession::where('user_id', $user->id)
            ->where('exam_id', $exam->id)
            ->where('status', 'ongoing')
            ->where('end_time', '>', Carbon::now())
            ->first();

        if ($activeSession) {
            return redirect()->route('exam.participate', $exam);
        }

        StudentExamSession::create([
            'user_id'             => $user->id,
            'exam_id'             => $exam->id,
            'status'              => 'ongoing',
            'start_time'          => Carbon::now(),
            'end_time'            => Carbon::now()->addMinutes($exam->duration_minutes),
            'total_questions'     => $exam->questions()->count(),
            'completed_questions' => 0,
            'progress_percentage' => 0,
            'last_activity_at'    => Carbon::now(),
        ]);

        return redirect()->route('exam.participate', $exam);
    }

    public function saveAnswer(Request $request, Exam $exam)
    {
        $user = Auth::user();
        
        $session = StudentExamSession::where('user_id', $user->id)
            ->where('exam_id', $exam->id)
            ->where('status', 'ongoing')
            ->latest('created_at')
            ->firstOrFail();

        if (Carbon::now()->gt($session->end_time)) {
            return response()->json(['status' => 'expired'], 403);
        }

        $content = trim((string)($request->option_id ?? $request->code ?? ''));

        StudentExamAnswer::updateOrCreate(
            [
                'student_exam_session_id' => $session->id,
                'question_id'             => $request->question_id
            ],
            [
                'user_id'            => $user->id,
                'exam_id'            => $exam->id,
                'selected_option_id' => $content,
            ]
        );

        if ($request->type === 'coding' && class_exists(CodingSubmission::class)) {
            
            $assessmentId = null;
            $q = DB::table('questions')->where('id', $request->question_id)->first();
            
            if ($q && $q->options) {
                $optData = json_decode($q->options, true);
                $assessmentId = $optData['assessment_id'] ?? ($optData['id'] ?? null);
            }

            if (!$assessmentId && $q) {
                $assessmentId = CodingAssessment::where('title', 'like', '%' . trim($q->question_text) . '%')->value('id');
            }

            if ($assessmentId) {
                CodingSubmission::updateOrCreate(
                    [
                        'user_id' => $user->id,
                        'coding_assessment_id' => $assessmentId
                    ],
                    [
                        'submitted_code' => $content,
                        'language'       => strtolower($request->language ?? 'javascript'),
                        'status'         => 'pending'
                    ]
                );
            }
        }

        $answeredCount = StudentExamAnswer::where('student_exam_session_id', $session->id)
            ->whereNotNull('selected_option_id')
            ->where('selected_option_id', '!=', '')
            ->count();
            
        $total = $session->total_questions > 0 ? $session->total_questions : 1; 
        $progress = round(($answeredCount / $total) * 100);

        $session->update([
            'completed_questions' => $answeredCount,
            'progress_percentage' => $progress,
            'last_activity_at'    => Carbon::now() 
        ]);

        return response()->json(['status' => 'saved', 'progress' => $progress]);
    }

    public function submitExam(Request $request, Exam $exam)
    {
        $user = Auth::user();
        
        $session = StudentExamSession::where('user_id', $user->id)
            ->where('exam_id', $exam->id)
            ->where('status', 'ongoing')
            ->latest('created_at')
            ->first();

        if ($session) {
            if (class_exists(CodingSubmission::class)) {
                $codingAnswers = StudentExamAnswer::where('student_exam_session_id', $session->id)
                    ->join('questions', 'student_exam_answers.question_id', '=', 'questions.id')
                    ->where('questions.type', 'coding')
                    ->select('student_exam_answers.*')
                    ->get();

                foreach ($codingAnswers as $ans) {
                    $q = DB::table('questions')->where('id', $ans->question_id)->first();
                    $assessmentId = null;
                    
                    if ($q && $q->options) {
                        $optData = json_decode($q->options, true);
                        $assessmentId = $optData['assessment_id'] ?? ($optData['id'] ?? null);
                    }

                    if (!$assessmentId && $q) {
                        $assessmentId = CodingAssessment::where('title', 'like', '%' . trim($q->question_text) . '%')->value('id');
                    }
                    
                    if ($assessmentId) {
                        CodingSubmission::updateOrCreate(
                            [
                                'user_id' => $user->id, 
                                'coding_assessment_id' => $assessmentId
                            ],
                            [
                                'submitted_code' => $ans->selected_option_id,
                                'language' => 'javascript',
                                'status' => 'pending'
                            ]
                        );
                    }
                }
            }

            $this->finalizeSession($session);
            
            if ($exam->is_paid) {
                DB::table('exam_user')
                    ->where('user_id', $user->id)
                    ->where('exam_id', $exam->id)
                    ->update(['status' => 'completed']);
            }
            
            return redirect()->route('user.results.show', $session->id);
        }
        
        return redirect()->route('my.exams');
    }

    protected function finalizeSession(StudentExamSession $session)
    {
        $session->update([
            'status' => 'completed', 
            'end_time' => Carbon::now(),
            'last_activity_at' => Carbon::now()
        ]);
    }
    
    private function prepareQuestions($exam, $seed = null)
    {
        $query = $exam->questions();
        if ($seed) {
            $query->inRandomOrder($seed);
        } else {
            $query->orderBy('id');
        }

        $isAddonActive = class_exists(CodingAssessment::class);

        return $query->get()->map(function($q) use ($isAddonActive) {
            $rawOptions = $q->options;
            if (is_string($rawOptions)) {
                $decoded = json_decode($rawOptions, true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    $rawOptions = $decoded;
                }
            }
            
            $formattedOptions = [];
            $codingData = null;
            $assessmentId = null;

            if ($q->type === 'coding' && $isAddonActive) {
                $codingData = is_array($rawOptions) ? $rawOptions : [];
                $assessmentId = $codingData['assessment_id'] ?? ($codingData['id'] ?? null);
                
                if (!$assessmentId) {
                    $assessmentId = CodingAssessment::where('title', 'like', '%' . trim($q->question_text) . '%')->value('id');
                }
                
                $codingData['assessment_id'] = $assessmentId;

                if (isset($codingData['test_cases']) && is_string($codingData['test_cases'])) {
                    $codingData['test_cases'] = json_decode($codingData['test_cases'], true);
                }
            } elseif (is_array($rawOptions)) {
                foreach ($rawOptions as $key => $value) {
                    $formattedOptions[] = [
                        'id' => (string)$key, 
                        'option_text' => is_array($value) ? ($value['option_text'] ?? ($value['text'] ?? null)) : $value
                    ];
                }
            }

            $isRichMedia = !empty($q->media_type) && $q->media_type !== 'text';
            $formattedQuestion = [
                'id' => $q->id,
                'question_text' => $q->question_text,
                'options' => $formattedOptions,
                'type' => $q->type,
                'coding_config' => $codingData,
                'assessment_id' => $assessmentId
            ];
            
            if ($isRichMedia) {
                $formattedQuestion['media_type'] = $q->media_type;
                $formattedQuestion['media_url'] = $q->media_url;
                $formattedQuestion['math_content'] = $q->math_content;
                $formattedQuestion['option_media'] = $q->option_media ? json_decode($q->option_media, true) : null;
            }
            return $formattedQuestion;
        })->values();
    }
}