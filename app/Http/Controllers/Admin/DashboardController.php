<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Exam;
use App\Models\Payment;
use App\Models\StudentExamSession;
use App\Models\Plan;
use App\Models\SystemSetting;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Str;

class DashboardController extends Controller
{
    public function index()
    {
        $settings = SystemSetting::pluck('value', 'key')->toArray();
        $currencySymbol = $settings['currency_symbol'] ?? '$';

        $totalCandidates = User::role('Student')->count();
        $newCandidates = User::role('Student')
            ->where('created_at', '>=', Carbon::now()->subMonth())
            ->count();
        
        $growthPercentage = $totalCandidates > 0 
            ? round(($newCandidates / $totalCandidates) * 100, 1) 
            : 0;

        $completedSessions = StudentExamSession::where('status', 'completed')->get();
        $passedSessions = $completedSessions->where('is_passed', true)->count();
        $totalCompleted = $completedSessions->count();
        $passRate = $totalCompleted > 0 ? round(($passedSessions / $totalCompleted) * 100, 1) : 0;

        $liveCount = StudentExamSession::where('status', 'ongoing')->count();

        $topLiveExamId = StudentExamSession::where('status', 'ongoing')
            ->select('exam_id', DB::raw('count(*) as total'))
            ->groupBy('exam_id')
            ->orderByDesc('total')
            ->first();

        $activeExam = null;
        if ($topLiveExamId) {
            $activeExam = Exam::with(['category'])->withCount(['sessions as active_count' => function($q){
                $q->where('status', 'ongoing');
            }])->find($topLiveExamId->exam_id);
            
            $activeExam->remaining_formatted = $this->formatDuration($activeExam->duration_minutes);
        }

        $chartData = $this->getChartData();

        $totalRevenue = Payment::where('status', 'success')->sum('amount');
        $pendingPayments = Payment::where('status', 'pending')->count();
        $activePlans = Plan::where('is_active', true)->count();
        
        $paidPlanIds = Plan::where('is_active', true)
            ->where(function($q) {
                $q->where('price_monthly', '>', 0)
                  ->orWhere('price_yearly', '>', 0);
            })
            ->pluck('id');

        $premiumUsers = User::role('Student')->whereIn('plan_id', $paidPlanIds)->count();
        $basicUsers = User::role('Student')
            ->where(function($q) use ($paidPlanIds) {
                $q->whereNull('plan_id')->orWhereNotIn('plan_id', $paidPlanIds);
            })->count();

        $totalSubUsers = $premiumUsers + $basicUsers;
        $premiumPercentage = $totalSubUsers > 0 ? round(($premiumUsers / $totalSubUsers) * 100) : 0;

        $riskData = $this->getAiRiskData();

        $upcomingExams = Exam::where('is_active', true)
            ->where('start_date', '>', Carbon::now())
            ->orderBy('start_date', 'asc')
            ->take(5)
            ->get();

        return view('admin.dashboard.index', compact(
            'totalCandidates',
            'growthPercentage',
            'passRate',
            'liveCount',
            'activeExam',
            'chartData',
            'totalRevenue',
            'pendingPayments',
            'activePlans',
            'premiumUsers',
            'basicUsers',
            'premiumPercentage',
            'riskData',
            'upcomingExams',
            'currencySymbol'
        ));
    }

    private function getChartData()
    {
        $data = [];
        $months = collect(range(11, 0))->map(fn($i) => Carbon::today()->startOfMonth()->subMonths($i));

        foreach ($months as $month) {
            $start = $month->copy()->startOfMonth();
            $end = $month->copy()->endOfMonth();

            $attendance = StudentExamSession::whereBetween('created_at', [$start, $end])->count();
            
            $monthSessions = StudentExamSession::whereBetween('updated_at', [$start, $end])
                ->where('status', 'completed')
                ->get();
            
            $passRate = $monthSessions->count() > 0 
                ? round(($monthSessions->where('is_passed', true)->count() / $monthSessions->count()) * 100) 
                : 0;

            $data[] = [
                'label' => $month->format('M'),
                'full_date' => $month->format('F Y'),
                'attendance_val' => $attendance,
                'attendance_height' => $attendance > 0 ? min($attendance, 100) : 5,
                'pass_val' => $passRate,
                'pass_height' => $passRate,
                'is_current' => $month->isCurrentMonth()
            ];
        }
        return $data;
    }

    private function getAiRiskData()
    {
        $rawAlerts = StudentExamSession::where('status', 'ongoing')
            ->whereIn('risk_level', ['critical', 'warning'])
            ->with(['user', 'exam'])
            ->orderByDesc('risk_score')
            ->take(5)
            ->get();

        $alerts = $rawAlerts->map(function ($session) {
            $events = $session->flagged_events ?? [];
            $latestEvent = !empty($events) ? end($events) : 'Anomaly detected via heuristics.';
            
            $cleanMessage = preg_replace('/^\[.*?\]\s*/', '', $latestEvent);

            $title = __('dashboard.risk_general');
            if (Str::contains(strtolower($cleanMessage), 'tab')) {
                $title = __('dashboard.risk_tab_switch');
            } elseif (Str::contains(strtolower($cleanMessage), ['ip', 'location'])) {
                $title = __('dashboard.risk_ip_mismatch');
            } elseif (Str::contains(strtolower($cleanMessage), ['face', 'person'])) {
                $title = __('dashboard.risk_face_detection');
            }

            return (object) [
                'id' => $session->id,
                'level' => $session->risk_level,
                'title' => $title,
                'message' => $cleanMessage . " ({$session->user->name})",
                'user_name' => $session->user->name,
                'exam_title' => $session->exam->title
            ];
        });

        return [
            'count' => $rawAlerts->count(),
            'alerts' => $alerts
        ];
    }

    private function formatDuration($minutes)
    {
        $h = floor($minutes / 60);
        $m = $minutes % 60;
        return $h > 0 ? "{$h}h {$m}m" : "{$m}m";
    }
}