<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\StudentExamSession;
use App\Models\Result; 
use App\Models\LoginHistory;
use App\Models\User;
use App\Models\SystemSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class ReportController extends Controller
{
    public function subscriptionHistory(Request $request)
    {
        // Filter strictly for subscriptions to separate from one-time purchases
        $query = Payment::with(['user', 'plan'])
            ->where('type', 'subscription')
            ->latest();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('transaction_id', 'like', "%{$search}%")
                  ->orWhereHas('user', function($user) use ($search) {
                      $user->where('name', 'like', "%{$search}%")
                           ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        $payments = $query->paginate(15)->withQueryString();

        // Financial KPIs
        $successfulSubs = Payment::where('type', 'subscription')->whereIn('status', ['success', 'approved']);
        
        $totalRevenue = $successfulSubs->sum('amount');
        $activeSubscribers = $successfulSubs->where('end_date', '>', now())->distinct('user_id')->count('user_id');
        $totalTransactions = Payment::where('type', 'subscription')->count();

        $settings = SystemSetting::getSettings();
        $currencySymbol = $settings['currency_symbol'] ?? '$';

        $kpis = [
            [
                'label' => 'Total Revenue',
                'value' => $currencySymbol . number_format($totalRevenue, 2),
                'icon'  => 'fa-solid fa-sack-dollar',
                'color' => 'success',
            ],
            [
                'label' => 'Active Subscribers',
                'value' => number_format($activeSubscribers),
                'icon'  => 'fa-solid fa-users-viewfinder',
                'color' => 'primary',
            ],
            [
                'label' => 'Total Transactions',
                'value' => number_format($totalTransactions),
                'icon'  => 'fa-solid fa-receipt',
                'color' => 'neutral', // Custom neutral style or defaults to gray
            ],
        ];

        // Currency Formatting Config
        $currencyConfig = [
            'symbol'    => $currencySymbol,
            'position'  => $settings['currency_position'] ?? 'before',
            'decimal'   => $settings['decimal_separator'] ?? '.',
            'thousands' => $settings['thousands_separator'] ?? ',',
        ];

        return view('admin.reports.subscriptions', compact('payments', 'kpis', 'currencyConfig'));
    }

    public function examHistory(Request $request)
    {
        $query = StudentExamSession::where('status', 'completed')
            ->with(['user', 'exam', 'result']); 

        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->whereHas('user', function($u) use ($searchTerm) {
                    $u->where('name', 'like', '%' . $searchTerm . '%')
                      ->orWhere('email', 'like', '%' . $searchTerm . '%');
                })
                ->orWhereHas('exam', function($e) use ($searchTerm) {
                    $e->where('title', 'like', '%' . $searchTerm . '%');
                });
            });
        }
        
        $results = $query->latest('updated_at')->paginate(15)->withQueryString();

        $totalResults = Result::count();
        $passedCount  = Result::where('is_passed', true)->count();
        $failedCount  = Result::where('is_passed', false)->count();
        $passRate     = $totalResults > 0 ? round(($passedCount / $totalResults) * 100) : 0;

        $kpis = [
            [
                'label' => 'Total Attempts',
                'value' => number_format($totalResults),
                'icon'  => 'fa-solid fa-file-signature',
                'color' => 'primary',
            ],
            [
                'label' => 'Average Pass Rate',
                'value' => $passRate . '%',
                'icon'  => 'fa-solid fa-chart-line',
                'color' => 'success',
            ],
            [
                'label' => 'Passed Submissions',
                'value' => number_format($passedCount),
                'icon'  => 'fa-solid fa-circle-check',
                'color' => 'success',
            ],
            [
                'label' => 'Failed Submissions',
                'value' => number_format($failedCount),
                'icon'  => 'fa-solid fa-circle-xmark',
                'color' => 'danger',
            ]
        ];

        return view('admin.reports.exam_history', compact('results', 'kpis'));
    }
    
    public function loginHistory(Request $request)
    {
        $query = LoginHistory::with('user')->latest('login_at');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('ip_address', 'like', "%{$search}%")
                  ->orWhere('browser', 'like', "%{$search}%")
                  ->orWhere('country', 'like', "%{$search}%")
                  ->orWhereHas('user', function($u) use ($search) {
                      $u->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }
        
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }
        
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }
        
        $dateRange = $request->input('date_range', '30_days');
        
        if ($dateRange === '7_days') {
            $query->where('login_at', '>=', now()->subDays(7));
        } elseif ($dateRange === '30_days') {
            $query->where('login_at', '>=', now()->subDays(30));
        }

        $logs = $query->paginate(20)->withQueryString();

        $start = now()->subDays(30);
        $kpis = [
            [
                'label' => 'Total Logins (30 Days)',
                'value' => number_format(LoginHistory::where('login_at', '>=', $start)->count()),
                'icon'  => 'fa-solid fa-right-to-bracket',
                'color' => 'primary',
            ],
            [
                'label' => 'Unique Devices',
                'value' => number_format(LoginHistory::where('login_at', '>=', $start)->distinct()->count('user_agent')),
                'icon'  => 'fa-solid fa-mobile-alt',
                'color' => 'success',
            ],
            [
                'label' => 'Failed Logins',
                'value' => number_format(LoginHistory::where('login_at', '>=', $start)->where('status', 'failed')->count()),
                'icon'  => 'fa-solid fa-user-lock',
                'color' => 'danger',
            ],
            [
                'label' => 'Suspicious Attempts',
                'value' => number_format(LoginHistory::where('login_at', '>=', $start)->where('status', 'suspicious')->count()),
                'icon'  => 'fa-solid fa-triangle-exclamation',
                'color' => 'warning',
            ],
        ];
        
        $users = User::select('id', 'name', 'email')->get();

        return view('admin.reports.login_history', compact('logs', 'kpis', 'users', 'dateRange'));
    }
}