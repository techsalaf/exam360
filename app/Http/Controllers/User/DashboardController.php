<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Exam;
use App\Models\StudentExamSession;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Fetch Notifications (New System)
        $notifications = $user->notifications()->latest()->take(5)->get();

        // Exam Logic
        $purchasedExams = $user->exams()
            ->where('is_active', true)
            ->with(['category'])
            ->withCount('questions')
            ->get();

        $sessionExamIds = StudentExamSession::where('user_id', $user->id)
            ->pluck('exam_id')
            ->unique();

        $startedExams = Exam::whereIn('id', $sessionExamIds)
            ->where('is_active', true)
            ->with(['category'])
            ->withCount('questions')
            ->get();

        $allExams = $purchasedExams->merge($startedExams)->unique('id');

        $categorized = $this->categorizeExams($user->id, $allExams);
        
        $completedExamsList = $categorized['completed'];
        $scheduledCount = $categorized['ongoing']->count() + $categorized['upcoming']->count();
        
        $avgScore = $completedExamsList->isNotEmpty() 
            ? $completedExamsList->avg(fn($exam) => optional($exam->user_session)->score ?? 0) 
            : 0;
        
        // --- FIX: ADD MISSING UI STATS ---
        $roundedAvgScore = round($avgScore, 0);

        $stats = [
            'scheduled_count' => $scheduledCount,
            'completed_count' => $completedExamsList->count(),
            'average_score'   => $roundedAvgScore,
            // Add missing keys, using average score as a simple placeholder
            'accuracy'        => $roundedAvgScore,
            'time_management' => $roundedAvgScore > 50 ? round($roundedAvgScore * 0.9, 0) : round($roundedAvgScore * 1.2, 0), // Simple mock data
            'consistency'     => $roundedAvgScore > 50 ? round($roundedAvgScore * 0.8, 0) : round($roundedAvgScore * 1.1, 0),
        ];
        // ---------------------------------
        
        $nextExam = null;
        $nextExamRoute = '#';

        if ($categorized['ongoing']->isNotEmpty()) {
            $nextExam = $categorized['ongoing']->first();
            $nextExam->status = 'ongoing'; // FIX: Use lowercase status for Blade comparison
            $nextExam->status_color = 'warning';
            $nextExam->display_date = 'Ends: ' . Carbon::parse($nextExam->user_session->end_time)->format('M d, h:i A');
            $nextExamRoute = route('exam.participate', $nextExam->id);
        } elseif ($categorized['upcoming']->isNotEmpty()) {
            $nextExam = $categorized['upcoming']->first();
            
            $isFuture = $nextExam->start_date && Carbon::parse($nextExam->start_date)->isFuture();
            
            if ($isFuture) {
                $nextExam->status = 'upcoming'; // FIX: Use lowercase status for Blade comparison
                $nextExam->status_color = 'success';
                $nextExam->display_date = Carbon::parse($nextExam->start_date)->format('l, M d, h:i A');
                $nextExamRoute = route('exam.participate', $nextExam->id);
            } else {
                $nextExam->status = 'ready'; // FIX: Use lowercase status for Blade comparison
                $nextExam->status_color = 'primary';
                $nextExam->display_date = 'Available Now';
                $nextExamRoute = route('exam.participate', $nextExam->id);
            }
        }

        $upcomingExams = $categorized['upcoming']->take(3); 
        $completedExams = $categorized['completed']->take(3);

        return view('user.dashboard.index', compact(
            'stats', 
            'upcomingExams', 
            'completedExams', 
            'nextExam', 
            'nextExamRoute',
            'notifications'
        ));
    }

    private function categorizeExams($userId, $exams)
    {
        $data = [
            'ongoing'   => collect(), 
            'completed' => collect(), 
            'upcoming'  => collect(),
        ];
        
        $sessions = StudentExamSession::where('user_id', $userId)
            ->whereIn('exam_id', $exams->pluck('id'))
            ->latest('created_at')
            ->get()
            ->unique('exam_id')
            ->keyBy('exam_id');

        $now = Carbon::now();

        foreach ($exams as $exam) {
            $session = $sessions->get($exam->id);
            $exam->user_session = $session;
            $exam->start_date_parsed = $exam->start_date ? Carbon::parse($exam->start_date) : null;

            if ($session) {
                if ($session->status === 'completed') {
                    $data['completed']->push($exam);
                    continue;
                }
                
                if ($session->status === 'ongoing') {
                     $endTime = $session->end_time ? Carbon::parse($session->end_time) : null;
                     if ($endTime && $now->gt($endTime)) {
                         $data['completed']->push($exam); 
                     } else {
                         $data['ongoing']->push($exam);
                     }
                     continue;
                }
            }

            $data['upcoming']->push($exam);
        }

        $data['upcoming'] = $data['upcoming']->sortBy(function ($exam) {
            return $exam->start_date_parsed && $exam->start_date_parsed->isFuture() 
                ? $exam->start_date_parsed->timestamp 
                : 0; 
        });
        
        $data['completed'] = $data['completed']->sortByDesc(function($exam) {
            return optional($exam->user_session)->updated_at;
        });

        return $data;
    }
}