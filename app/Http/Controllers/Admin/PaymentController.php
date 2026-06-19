<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\SystemSetting;
use App\Notifications\SystemNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Symfony\Component\HttpFoundation\StreamedResponse;
use App\Http\Controllers\Frontend\CheckoutController;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $query = $this->applyFilters(Payment::with('user')->latest(), $request);
        $globalSettings = SystemSetting::getSettings();
        $systemFormatting = [
            'symbol' => $globalSettings['currency_symbol'] ?? '$',
            'position' => $globalSettings['currency_position'] ?? 'before',
            'code' => $globalSettings['currency_code'] ?? 'USD',
            'decimal_sep' => $globalSettings['decimal_separator'] ?? '.',
            'thousands_sep' => $globalSettings['thousands_separator'] ?? ',',
        ];
        $formatCurrency = function ($amount, $symbol, $position, $decimal_sep, $thousands_sep) {
            $formattedAmount = number_format($amount, 2, $decimal_sep, $thousands_sep);
            return match($position) {
                'after' => $formattedAmount . $symbol,
                'before_space' => $symbol . ' ' . $formattedAmount,
                'after_space' => $formattedAmount . ' ' . $symbol,
                default => $symbol . $formattedAmount,
            };
        };
        $getDisplaySymbol = $systemFormatting['symbol']; 
        $payments = $query->paginate(15)->withQueryString();
        $stats = [
            [
                'label' => 'Successful Payments',
                'amount' => Payment::whereIn('status', ['success', 'successful', 'approved'])->sum('amount'),
                'icon'  => 'fa-solid fa-circle-check',
                'color' => 'success',
                'key'   => 'successful'
            ],
            [
                'label' => 'Pending Payments',
                'amount' => Payment::where('status', 'pending')->sum('amount'),
                'icon'  => 'fa-solid fa-hourglass-half',
                'color' => 'warning',
                'key'   => 'pending'
            ],
            [
                'label' => 'Rejected Payments',
                'amount' => Payment::whereIn('status', ['failed', 'rejected'])->sum('amount'),
                'icon'  => 'fa-solid fa-circle-xmark',
                'color' => 'danger',
                'key'   => 'rejected'
            ],
            [
                'label' => 'Initiated Payments',
                'amount' => Payment::where('status', 'initiated')->sum('amount'),
                'icon'  => 'fa-solid fa-bolt',
                'color' => 'info',
                'key'   => 'initiated'
            ]
        ];
        $stats = array_map(function ($stat) use ($systemFormatting, $formatCurrency) {
            $stat['display_amount'] = $formatCurrency(
                $stat['amount'], 
                $systemFormatting['symbol'], 
                $systemFormatting['position'],
                $systemFormatting['decimal_sep'],
                $systemFormatting['thousands_sep']
            );
            return $stat;
        }, $stats);
        $currentStatus = $request->status ? ucfirst($request->status) : 'All';
        $pageTitle = "{$currentStatus} Payments";
        return view('admin.payments.index', compact('payments', 'stats', 'pageTitle', 'systemFormatting', 'formatCurrency', 'getDisplaySymbol'));
    }

    public function export(Request $request)
    {
        $query = $this->applyFilters(Payment::with('user')->latest(), $request);
        $fileName = 'payments_export_' . date('Y-m-d_H-i') . '.csv';
        return new StreamedResponse(function () use ($query) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, [
                'Transaction ID',
                'Gateway',
                'User Name',
                'User Email',
                'Amount',
                'Currency',
                'Status',
                'Date'
            ]);
            $query->chunk(500, function ($payments) use ($handle) {
                foreach ($payments as $payment) {
                    fputcsv($handle, [
                        $payment->transaction_id,
                        ucfirst(str_replace('_', ' ', $payment->gateway)),
                        $payment->user ? $payment->user->name : 'Deleted User',
                        $payment->user ? $payment->user->email : 'N/A',
                        $payment->amount,
                        $payment->currency,
                        ucfirst($payment->status),
                        $payment->created_at->format('Y-m-d H:i:s'),
                    ]);
                }
            });
            fclose($handle);
        }, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$fileName}\"",
        ]);
    }

    private function applyFilters($query, Request $request)
    {
        if ($request->has('status') && $request->status !== 'all') {
            $status = $request->status;
            if (in_array($status, ['successful', 'approved'])) {
                $query->whereIn('status', ['success', 'successful', 'approved']);
            } elseif (in_array($status, ['rejected', 'failed'])) {
                $query->whereIn('status', ['rejected', 'failed']);
            } else {
                $query->where('status', $status);
            }
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('transaction_id', 'like', "%{$search}%")
                  ->orWhereHas('user', function($u) use ($search) {
                      $u->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }
        return $query;
    }

    public function approve(Payment $payment)
    {
        if ($payment->status !== 'pending') {
            return back()->with('error', 'Payment status cannot be changed.');
        }
        DB::beginTransaction();
        try {
            $payment->update(['status' => 'approved']);
            $checkoutController = new CheckoutController();
            $checkoutController->syncPaymentToPivotRecords($payment);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to approve payment and grant access: ' . $e->getMessage());
        }
        if ($payment->user) {
            $payment->user->notify(new SystemNotification('payment', [
                'title'   => 'Payment Approved',
                'message' => "Your payment of {$payment->amount} {$payment->currency} has been approved.",
                'url'     => route('user.transactions'),
                'icon'    => 'fa-solid fa-receipt',
                'color'   => 'success'
            ]));
        }
        return back()->with('success', 'Payment approved and product access granted.');
    }
    
    public function forceSync(Payment $payment)
    {
        if (!in_array($payment->status, ['approved', 'success', 'paid'])) {
            return back()->with('error', 'Only approved or successful payments can be synchronized.');
        }
        DB::beginTransaction();
        try {
            $checkoutController = new CheckoutController();
            $checkoutController->syncPaymentToPivotRecords($payment);
            DB::commit();
            return back()->with('success', 'Product access successfully synchronized (active) for transaction ID: ' . $payment->transaction_id);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Synchronization failed: ' . $e->getMessage());
        }
    }

    public function reject(Payment $payment)
    {
        if ($payment->status !== 'pending') {
            return back()->with('error', 'Payment status cannot be changed.');
        }
        $payment->update(['status' => 'rejected']);
        
        DB::table('exam_user')
            ->where('transaction_id', $payment->transaction_id)
            ->update([
                'status' => 'rejected',
                'updated_at' => now()
            ]);
            
        DB::table('ielts_test_user')
            ->where('transaction_id', $payment->transaction_id)
            ->update([
                'status' => 'rejected',
                'updated_at' => now()
            ]);

        if (Schema::hasTable('lms_enrollments')) {
            DB::table('lms_enrollments')
                ->where('user_id', $payment->user_id)
                ->where('status', 'pending')
                ->update([
                    'status' => 'rejected',
                    'updated_at' => now()
                ]);
        }

        if ($payment->user) {
            $payment->user->notify(new SystemNotification('payment', [
                'title'   => 'Payment Rejected',
                'message' => "Your payment of {$payment->amount} {$payment->currency} was rejected. Please contact support.",
                'url'     => route('user.tickets'),
                'icon'    => 'fa-solid fa-circle-xmark',
                'color'   => 'danger'
            ]));
        }
        return back()->with('success', 'Payment rejected successfully.');
    }
}