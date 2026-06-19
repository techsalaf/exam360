<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StudentExamSession;
use App\Models\Exam;
use App\Models\User;
use App\Services\AI\AICheatingDetector;
use App\Notifications\SystemNotification;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Cache;

class LiveExamController extends Controller
{
    public function index(): View
    {
        Gate::authorize('monitor_live_exams');

        $kpi = $this->getKpiStats();
        
        $sessions = StudentExamSession::with(['user', 'exam'])
            ->whereIn('status', ['ongoing', 'paused'])
            ->orderByRaw('COALESCE(last_activity_at, created_at) DESC')
            ->paginate(15);
        
        $runningExams = Exam::where('is_active', true)
            ->whereNotNull('start_date')
            ->where('start_date', '<=', now())
            ->where(function($q) {
                $q->whereNull('end_date')
                  ->orWhere('end_date', '>=', now());
            })
            ->withCount(['sessions as active_students' => function($q) {
                $q->where('status', 'ongoing');
            }])
            ->get();

        $exams = Exam::orderBy('title')->get(['id', 'title']);

        return view('admin.live.index', compact('sessions', 'kpi', 'exams', 'runningExams'));
    }

    public function action(Request $request, int $id): JsonResponse
    {
        Gate::authorize('monitor_live_exams');

        $request->validate([
            'action' => 'required|in:pause,resume,terminate,reopen'
        ]);

        try {
            $session = StudentExamSession::findOrFail($id);

            switch ($request->action) {
                case 'pause':
                    $session->update(['status' => 'paused']);
                    $message = 'Exam session paused successfully.';
                    break;

                case 'resume':
                    $session->update(['status' => 'ongoing']);
                    $message = 'Exam session resumed.';
                    break;

                case 'terminate':
                    $session->update([
                        'status' => 'terminated',
                        'end_time' => now()
                    ]);
                    $message = 'Exam force-submitted successfully.';
                    break;
                    
                case 'reopen':
                    if (!in_array($session->status, ['terminated', 'completed'])) {
                        return response()->json(['status' => 'error', 'message' => 'Only finished exams can be reopened.'], 400);
                    }

                    $session->update([
                        'status' => 'ongoing',
                        'end_time' => null, 
                        'last_activity_at' => now()
                    ]);
                    $message = 'Exam session successfully reopened and set to ongoing.';
                    break;
                    
                default:
                    return response()->json(['status' => 'error', 'message' => 'Invalid action'], 400);
            }

            return response()->json([
                'status' => 'success', 
                'message' => $message
            ]);
        } catch (\Exception $e) {
            Log::error("Live Exam Action Error: " . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Action failed.'], 500);
        }
    }

    public function fetchUpdates(Request $request, AICheatingDetector $detector): JsonResponse
    {
        Gate::authorize('monitor_live_exams');

        try {
            $sessionsToProcess = StudentExamSession::whereIn('status', ['ongoing', 'paused'])->get();
            
            foreach ($sessionsToProcess as $session) {
                try {
                    $detector->analyzeSession($session);

                    if ($session->risk_level === 'critical') {
                        $cacheKey = 'notified_risk_' . $session->id;
                        
                        // Prevent spamming notification: Only send once per 30 minutes for the same session
                        if (!Cache::has($cacheKey)) {
                            $admins = User::role('Super Admin')->get();
                            if($admins->isNotEmpty()){
                                Notification::send($admins, new SystemNotification('live', [
                                    'title'   => 'Critical Cheating Risk',
                                    'message' => "Session ID #{$session->id} ({$session->user->name}) has been flagged with Critical risk.",
                                    'url'     => route('admin.live.index', ['search' => $session->user->email]),
                                    'icon'    => 'fa-solid fa-triangle-exclamation',
                                    'color'   => 'danger'
                                ]));
                                
                                Cache::put($cacheKey, true, now()->addMinutes(30));
                            }
                        }
                    }

                } catch (\Exception $e) {
                    Log::warning("AI Analysis warning for session {$session->id}: " . $e->getMessage());
                }
            }

            $kpi = $this->getKpiStats();

            $query = StudentExamSession::with(['user', 'exam']);

            if ($request->filled('exam_id') && $request->exam_id !== 'all') {
                $query->where('exam_id', $request->exam_id);
            }

            if ($request->filled('status') && $request->status !== 'all') {
                $query->where('status', $request->status);
            } else {
                $query->whereIn('status', ['ongoing', 'paused']);
            }

            if ($request->filled('risk_level') && $request->risk_level !== 'all') {
                $query->where('risk_level', $request->risk_level); 
            }

            if ($request->filled('search')) {
                $search = $request->search;
                $query->whereHas('user', function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                });
            }

            $sessions = $query->orderByDesc('risk_score')
                              ->orderByRaw('COALESCE(last_activity_at, created_at) DESC')
                              ->get(); 

            $html = view('admin.live.partials.table-rows', compact('sessions'))->render();

            return response()->json([
                'status' => 'success',
                'kpi' => $kpi,
                'html' => $html,
                'timestamp' => now()->format('h:i:s A')
            ]);

        } catch (\Exception $e) {
            Log::error("Live Dashboard Fetch Error: " . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Server Error'
            ], 500);
        }
    }

    private function getKpiStats(): array
    {
        return [
            'active_users'    => StudentExamSession::where('status', 'ongoing')->count(),
            'critical_risk'   => StudentExamSession::where('status', 'ongoing')->where('risk_level', 'critical')->count(),
            'paused'          => StudentExamSession::where('status', 'paused')->count(),
            'completed_today' => StudentExamSession::whereIn('status', ['completed', 'terminated'])
                                    ->whereDate('updated_at', Carbon::today())
                                    ->count(),
        ];
    }
}