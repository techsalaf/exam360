<?php

namespace App\Http\Controllers\Admin\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SystemSetting;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Artisan;

class PaymentSettingsController extends Controller
{
    public static function getCurrencies()
    {
        return [
            'USD' => ['name' => 'US Dollar', 'symbol' => '$'],
            'EUR' => ['name' => 'Euro', 'symbol' => '€'],
            'GBP' => ['name' => 'British Pound', 'symbol' => '£'],
            'JPY' => ['name' => 'Japanese Yen', 'symbol' => '¥'],
            'CAD' => ['name' => 'Canadian Dollar', 'symbol' => 'C$'],
            'AUD' => ['name' => 'Australian Dollar', 'symbol' => 'A$'],
            'CHF' => ['name' => 'Swiss Franc', 'symbol' => 'CHF'],
            'CNY' => ['name' => 'Chinese Yuan', 'symbol' => '¥'],
            'HKD' => ['name' => 'Hong Kong Dollar', 'symbol' => 'HK$'],
            'INR' => ['name' => 'Indian Rupee', 'symbol' => '₹'],
            'BRL' => ['name' => 'Brazilian Real', 'symbol' => 'R$'],
            'RUB' => ['name' => 'Russian Ruble', 'symbol' => '₽'],
            'TRY' => ['name' => 'Turkish Lira', 'symbol' => '₺'],
            'ZAR' => ['name' => 'South African Rand', 'symbol' => 'R'],
        ];
    }
    
    protected function getGatewayValidationRules(): array
    {
        return [
            'payment_stripe_enable' => 'required|in:0,1',
            'payment_paypal_enable' => 'required|in:0,1',
            'payment_razorpay_enable' => 'required|in:0,1',
            'payment_offline_enable' => 'required|in:0,1',
            'payment_payu_enable' => 'required|in:0,1',
            'payment_paystack_enable' => 'required|in:0,1',
            'payment_flutterwave_enable' => 'required|in:0,1',
            'payment_mollie_enable' => 'required|in:0,1',
            'payment_paddle_enable' => 'required|in:0,1',
            
            'payment_stripe_public_key' => 'required_if:payment_stripe_enable,1|nullable|string|max:255',
            'payment_stripe_secret_key' => 'required_if:payment_stripe_enable,1|nullable|string|max:255',
            'payment_stripe_webhook_secret' => 'nullable|string|max:255',
            
            'payment_paypal_client_id' => 'required_if:payment_paypal_enable,1|nullable|string|max:255',
            'payment_paypal_secret_key' => 'required_if:payment_paypal_enable,1|nullable|string|max:255',
            'payment_paypal_environment' => 'required_if:payment_paypal_enable,1|in:sandbox,live',
            
            'payment_razorpay_key_id' => 'required_if:payment_razorpay_enable,1|nullable|string|max:255',
            'payment_razorpay_key_secret' => 'required_if:payment_razorpay_enable,1|nullable|string|max:255',

            'payment_offline_account_holder' => 'required_if:payment_offline_enable,1|nullable|string|max:255',
            'payment_offline_bank_name' => 'nullable|string|max:255',
            'payment_offline_account_number' => 'nullable|string|max:255',
            'payment_offline_swift_code' => 'nullable|string|max:255',
            'payment_offline_instructions' => 'nullable|string|max:2000',

            'payment_payu_merchant_key' => 'required_if:payment_payu_enable,1|nullable|string|max:255',
            'payment_payu_salt' => 'required_if:payment_payu_enable,1|nullable|string|max:255',
            'payment_payu_environment' => 'required_if:payment_payu_enable,1|in:sandbox,live',

            'payment_paystack_public_key' => 'required_if:payment_paystack_enable,1|nullable|string|max:255',
            'payment_paystack_secret_key' => 'required_if:payment_paystack_enable,1|nullable|string|max:255',

            'payment_flutterwave_public_key' => 'required_if:payment_flutterwave_enable,1|nullable|string|max:255',
            'payment_flutterwave_secret_key' => 'required_if:payment_flutterwave_enable,1|nullable|string|max:255',
            'payment_flutterwave_encryption_key' => 'required_if:payment_flutterwave_enable,1|nullable|string|max:255',

            'payment_mollie_api_key' => 'required_if:payment_mollie_enable,1|nullable|string|max:255',

            'payment_paddle_vendor_id' => 'required_if:payment_paddle_enable,1|nullable|string|max:255',
            'payment_paddle_auth_code' => 'required_if:payment_paddle_enable,1|nullable|string|max:255',
            'payment_paddle_public_key' => 'nullable|string',
        ];
    }

    protected function determineUpdateContext(Request $request): string
    {
        if ($request->has('currency_code')) return 'currency';
        if ($request->has('tax_name')) return 'tax';
        return 'gateway'; 
    }

    public function update(Request $request)
    {
        $context = $this->determineUpdateContext($request);
        $fragment = '#pane-payment';
        $successMessage = '';

        if ($context === 'currency') {
            
            $request->validate([
                'currency_code' => 'required|string|max:5',
                'currency_position' => ['required', Rule::in(['before', 'after', 'before_space', 'after_space'])],
                'decimal_separator' => 'required|string|max:1',
                'thousands_separator' => 'required|string|max:1',
                'custom_currency_code' => 'nullable|string|max:5',
                'custom_currency_symbol' => 'nullable|string|max:4',
            ]);
            
            $currencies = self::getCurrencies(); 
            $currencyCode = $request->input('currency_code');
            $customSymbol = $request->input('custom_currency_symbol');

            if ($currencyCode === 'CUSTM' && !empty($request->input('custom_currency_code'))) {
                SystemSetting::set('currency_code', strtoupper($request->input('custom_currency_code')), 'payment');
                SystemSetting::set('currency_symbol', $customSymbol, 'payment');
            } elseif (isset($currencies[$currencyCode])) {
                SystemSetting::set('currency_code', $currencyCode, 'payment');
                SystemSetting::set('currency_symbol', $currencies[$currencyCode]['symbol'], 'payment');
                
                SystemSetting::set('custom_currency_code', '', 'payment');
                SystemSetting::set('custom_currency_symbol', '', 'payment');
            }

            SystemSetting::set('currency_position', $request->input('currency_position'), 'payment');
            SystemSetting::set('decimal_separator', $request->input('decimal_separator'), 'payment');
            SystemSetting::set('thousands_separator', $request->input('thousands_separator'), 'payment');

            $fragment = '#pane-currency';
            $successMessage = __('Currency settings updated successfully.');
        
        } elseif ($context === 'tax') {
            
            $request->merge([
                'tax_inclusive' => $request->has('tax_inclusive') ? '1' : '0'
            ]);

            $request->validate([
                'tax_name' => 'required|string|max:100',
                'tax_default_rate' => 'required|numeric|min:0|max:100',
                'tax_inclusive' => 'required|in:0,1',
            ]);

            SystemSetting::set('tax_name', $request->input('tax_name'), 'payment');
            SystemSetting::set('tax_default_rate', $request->input('tax_default_rate'), 'payment');
            SystemSetting::set('tax_inclusive', $request->input('tax_inclusive'), 'payment');

            $fragment = '#pane-tax';
            $successMessage = __('Tax configuration updated successfully.');
        
        } else {
            
            $request->merge([
                'payment_stripe_enable' => $request->boolean('payment_stripe_enable') ? '1' : '0',
                'payment_paypal_enable' => $request->boolean('payment_paypal_enable') ? '1' : '0',
                'payment_razorpay_enable' => $request->boolean('payment_razorpay_enable') ? '1' : '0',
                'payment_offline_enable' => $request->boolean('payment_offline_enable') ? '1' : '0',
                'payment_payu_enable' => $request->boolean('payment_payu_enable') ? '1' : '0',
                'payment_paystack_enable' => $request->boolean('payment_paystack_enable') ? '1' : '0',
                'payment_flutterwave_enable' => $request->boolean('payment_flutterwave_enable') ? '1' : '0',
                'payment_mollie_enable' => $request->boolean('payment_mollie_enable') ? '1' : '0',
                'payment_paddle_enable' => $request->boolean('payment_paddle_enable') ? '1' : '0',
            ]);

            $request->validate($this->getGatewayValidationRules());
            
            foreach ($request->all() as $key => $value) {
                if (Str::startsWith($key, 'payment_')) {
                    SystemSetting::set($key, $value, 'payment');
                }
            }

            $successMessage = __('Payment Gateway settings updated successfully.');
        }
        
        SystemSetting::clearCache();
        Cache::flush();
        try {
            Artisan::call('cache:clear');
        } catch (\Exception $e) {}

        return redirect()->route('admin.settings.index', ['#' => $fragment])->with('success', $successMessage);
    }
}