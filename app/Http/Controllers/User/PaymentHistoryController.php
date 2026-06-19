<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Payment;
use App\Models\SystemSetting;
use Carbon\Carbon;

class PaymentHistoryController extends Controller
{

    public function index(Request $request)
    {
        $userId = Auth::id();
        
        $query = Payment::where('user_id', $userId)

            ->latest();

        if ($request->filled('search')) {
            $query->where('transaction_id', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('date_from')) {
            try {
                $dateFrom = Carbon::parse($request->date_from)->startOfDay();
                $query->where('created_at', '>=', $dateFrom);
            } catch (\Exception $e) {}
        }

        if ($request->filled('date_to')) {
            try {
                $dateTo = Carbon::parse($request->date_to)->endOfDay();
                $query->where('created_at', '<=', $dateTo);
            } catch (\Exception $e) {}
        }
            
        $payments = $query->paginate(15)->withQueryString();
            
        $settings = SystemSetting::pluck('value', 'key')->toArray();

        $formatCurrency = function ($amount) use ($settings) {
            $symbol = $settings['currency_symbol'] ?? '$';
            $position = $settings['currency_position'] ?? 'before';
            $decimal_sep = $settings['decimal_separator'] ?? '.';
            $thousands_sep = $settings['thousands_separator'] ?? ',';
            
            $formattedAmount = number_format($amount, 2, $decimal_sep, $thousands_sep);

            return match($position) {
                'after' => $formattedAmount . $symbol,
                'before_space' => $symbol . ' ' . $formattedAmount,
                'after_space' => $formattedAmount . ' ' . $symbol,
                default => $symbol . $formattedAmount,
            };
        };

        return view('user.profile.transactions', compact('payments', 'formatCurrency'));
    }
}