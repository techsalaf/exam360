<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\User;
use App\Models\Payment;
use App\Models\SystemSetting;
use App\Models\Plan;
use IeltsModule\Models\IeltsTest; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Stripe\Stripe;
use Stripe\Charge;

class CheckoutController extends Controller
{
    private function getCheckoutData()
    {
        $cart = Session::get('cart', []);
        $settings = SystemSetting::pluck('value', 'key')->toArray();
        $subtotal = 0;
        foreach ($cart as $key => $item) {
            $subtotal += $item['price'];
            if (!isset($item['duration'])) {
                $cart[$key]['duration'] = 0;
            }
            if (!isset($item['questions'])) {
                $cart[$key]['questions'] = 0;
            }
            if ($item['type'] === 'plan' && !isset($item['plan_period'])) {
                 $cart[$key]['plan_period'] = Session::get('plan_period'); 
            }
        }
        Session::put('cart', $cart);
        $taxRate = (float) ($settings['tax_default_rate'] ?? 0);
        $currencySymbol = $settings['currency_symbol'] ?? '$';
        $tax = $subtotal * ($taxRate / 100);
        $total = $subtotal + $tax;
        $enabledGateways = [];
        if (($settings['payment_stripe_enable'] ?? '0') === '1' && !empty($settings['payment_stripe_public_key']) && !empty($settings['payment_stripe_secret_key'])) {
            $enabledGateways['stripe'] = [
                'name' => 'Credit/Debit Card (Stripe)', 
                'icon' => 'fa-credit-card', 
                'public_key' => $settings['payment_stripe_public_key'],
                'secret_key' => $settings['payment_stripe_secret_key'] 
            ];
        }
        if (($settings['payment_paypal_enable'] ?? '0') === '1' && !empty($settings['payment_paypal_client_id']) && !empty($settings['payment_paypal_secret_key'])) {
            $enabledGateways['paypal'] = [
                'name' => 'PayPal', 
                'icon' => 'fa-paypal',
                'client_id' => $settings['payment_paypal_client_id'],
                'secret' => $settings['payment_paypal_secret_key'],
                'mode' => $settings['payment_paypal_environment'] ?? 'sandbox'
            ];
        }
        if (($settings['payment_razorpay_enable'] ?? '0') === '1' && !empty($settings['payment_razorpay_key_id']) && !empty($settings['payment_razorpay_key_secret'])) {
            $enabledGateways['razorpay'] = [
                'name' => 'Razorpay', 
                'icon' => 'fa-indian-rupee-sign',
                'key_id' => $settings['payment_razorpay_key_id'],
                'key_secret' => $settings['payment_razorpay_key_secret']
            ];
        }
        if (($settings['payment_offline_enable'] ?? '0') === '1' && !empty($settings['payment_offline_account_holder'])) {
            $enabledGateways['offline'] = [
                'name' => 'Bank Transfer/Offline', 
                'icon' => 'fa-bank',
                'details' => [
                    'account_holder_name' => $settings['payment_offline_account_holder'] ?? '',
                    'bank_name' => $settings['payment_offline_bank_name'] ?? '',
                    'account_number_iban' => $settings['payment_offline_account_number'] ?? '',
                    'ifsc_swift_code' => $settings['payment_offline_swift_code'] ?? '',
                    'instructions' => $settings['payment_offline_instructions'] ?? '',
                ]
            ];
        }

        $addonActive = Schema::hasTable('addons') && DB::table('addons')->where('slug', 'payment-gateways')->where('is_active', 1)->exists();
        if ($addonActive) {
            if (($settings['payment_payu_enable'] ?? '0') === '1' && !empty($settings['payment_payu_merchant_key'])) {
                $enabledGateways['payu'] = ['name' => 'PayU', 'icon' => 'fa-credit-card'];
            }
            if (($settings['payment_paystack_enable'] ?? '0') === '1' && !empty($settings['payment_paystack_public_key'])) {
                $enabledGateways['paystack'] = ['name' => 'Paystack', 'icon' => 'fa-credit-card'];
            }
            if (($settings['payment_flutterwave_enable'] ?? '0') === '1' && !empty($settings['payment_flutterwave_public_key'])) {
                $enabledGateways['flutterwave'] = ['name' => 'Flutterwave', 'icon' => 'fa-credit-card'];
            }
            if (($settings['payment_mollie_enable'] ?? '0') === '1' && !empty($settings['payment_mollie_api_key'])) {
                $enabledGateways['mollie'] = ['name' => 'Mollie', 'icon' => 'fa-credit-card'];
            }
            if (($settings['payment_paddle_enable'] ?? '0') === '1' && !empty($settings['payment_paddle_vendor_id'])) {
                $enabledGateways['paddle'] = ['name' => 'Paddle', 'icon' => 'fa-credit-card'];
            }
        }

        return compact('cart', 'subtotal', 'tax', 'total', 'currencySymbol', 'taxRate', 'enabledGateways', 'settings');
    }

    public function addToCart($id, $type = 'exam')
    {
        if (request()->has('type')) {
            $type = request()->input('type');
        }
        $cart = Session::get('cart', []);
        $item = null;
        $title = '';
        $price = 0;
        $duration = 0;
        $questions = 0;
        $banner = null;
        $categoryName = '';
        $redirectRoute = 'checkout.cart';
        $foreignKey = '';
        $period = null;

        if ($type === 'plan') {
            foreach ($cart as $key => $cartItem) {
                if ($cartItem['type'] === 'plan') {
                    return redirect()->back()->with('info', 'You can only have one subscription plan in your cart at a time.');
                }
            }
        }

        if ($type === 'plan') {
            $period = request()->input('period', 'monthly'); 
            if (!in_array($period, ['monthly', 'yearly'])) {
                return redirect()->back()->with('error', 'Invalid subscription period selected.');
            }
            $item = Plan::findOrFail($id);
            $priceField = 'price_' . $period;
            $title = 'Subscription Plan: ' . $item->name . ' (' . ucfirst($period) . ')';
            $price = (float) $item->{$priceField};
            $duration = 0; 
            $questions = 0;
            $banner = null; 
            $categoryName = 'Subscription';
            $redirectRoute = 'user.dashboard';
            $foreignKey = 'plan_id';
            if (Auth::check() && Auth::user()->is_subscribed) {
                if (Auth::user()->plan_id == $id) {
                    return redirect()->back()->with('info', 'You are already subscribed to this plan.');
                }
            }
            Session::put('plan_period', $period); 
        } elseif ($type === 'ielts') {
            if (!Schema::hasTable('ielts_tests')) {
                return redirect()->back()->with('error', 'IELTS Addon is not installed.');
            }
            $item = IeltsTest::with('modules')->findOrFail($id);
            $title = 'IELTS Test: ' . $item->title;
            $price = (float) $item->price;
            $duration = $item->total_duration > 0 ? $item->total_duration : ($item->modules->sum('duration') + 10);
            $questions = 4;
            $banner = $item->banner_image;
            $categoryName = 'IELTS ' . ucfirst($item->type);
            $redirectRoute = 'user.addons.ielts-module.results';
            $foreignKey = 'ielts_test_id';
        } elseif ($type === 'course') {
            if (!Schema::hasTable('lms_courses')) {
                return redirect()->back()->with('error', 'LMS Addon is not installed.');
            }
            $item = DB::table('lms_courses')->where('id', $id)->first();
            if (!$item) abort(404);
            $title = $item->title;
            $price = (float) $item->price;
            $duration = 0;
            $questions = 0;
            $banner = $item->thumbnail;
            $categoryName = 'Course';
            $redirectRoute = 'lms.courses.index';
            $foreignKey = 'course_id';
        } else {
            $item = Exam::withCount('questions')->findOrFail($id);
            $title = $item->title;
            $price = (float) $item->price;
            $duration = $item->duration_minutes;
            $questions = $item->questions_count ?? 0;
            $banner = $item->banner;
            $categoryName = $item->category->name ?? 'Exam';
            $foreignKey = 'exam_id';
        }

        if ($price <= 0) {
            if ($type === 'ielts' && Route::has('user.addons.ielts-module.show')) {
                return redirect()->route('user.addons.ielts-module.show', $item->slug);
            } elseif ($type === 'plan') {
                 return redirect()->route('pricing.index');
            } elseif ($type === 'course' && Route::has('lms.courses.show')) {
                 return redirect()->route('lms.courses.show', $item->slug);
            } else {
                return redirect()->route('exam.participate', $item->slug);
            }
        }

        $key = $type . '-' . $id;
        if (isset($cart[$key])) {
            return redirect()->route('checkout.cart')->with('info', $title . ' is already in your cart.');
        }

        if (Auth::check() && $type !== 'plan') {
            $user = Auth::user();

            if ($type === 'exam' && $user->canAccessExam($item)) {
                return redirect()->route('exam.participate', $item->slug)
                    ->with('info', 'You already have access to this item.');
            }

            if ($type === 'ielts' && Schema::hasTable('ielts_test_user')) {
                $latestStatus = DB::table('ielts_test_user')
                    ->where('user_id', $user->id)
                    ->where('ielts_test_id', $id)
                    ->orderByDesc('created_at')
                    ->value('status');
                if ($latestStatus === 'active') {
                    return redirect()->route('user.addons.ielts-module.index')
                        ->with('info', 'You already have an active access for this item. Please complete your current test first.');
                }
            } elseif ($type === 'course' && Schema::hasTable('lms_enrollments')) {
                $isEnrolled = DB::table('lms_enrollments')
                    ->where('user_id', $user->id)
                    ->where('course_id', $id)
                    ->exists();
                if ($isEnrolled) {
                    return redirect()->route('lms.courses.show', $item->slug)->with('info', 'You are already enrolled in this course.');
                }
            } else {
                $hasActiveAccess = $user->exams()
                    ->where('exam_id', $id)
                    ->whereIn('exam_user.status', ['active', 'pending'])
                    ->exists();
                if ($hasActiveAccess) {
                    return redirect()->route($redirectRoute)->with('info', 'You already have an active or pending access for this item.');
                }
            }
        }

        $cart[$key] = [
            "id" => $id,
            "type" => $type,
            "title" => $title,
            "price" => $price,
            "duration" => $duration,
            "questions" => $questions,
            "banner" => $banner,
            "category_name" => $categoryName,
            "foreign_key" => $foreignKey,
            "plan_period" => $period
        ];
        Session::put('cart', $cart);
        return redirect()->route('checkout.cart')->with('success', 'Item added to cart.');
    }

    public function cart()
    {
        $data = $this->getCheckoutData();
        return view('frontend.checkout.cart', $data);
    }

    public function remove($key)
    {
        $cart = Session::get('cart', []);
        if (isset($cart[$key])) {
            if ($cart[$key]['type'] === 'plan') {
                Session::forget('plan_period');
            }
            unset($cart[$key]);
            Session::put('cart', $cart);
        }
        return redirect()->back()->with('success', 'Item removed from cart.');
    }

    public function details()
    {
        $data = $this->getCheckoutData();
        if (empty($data['cart'])) {
            return redirect()->route('home')->with('error', 'Your cart is empty.');
        }
        $data['countries'] = [
            'US' => 'United States', 'CA' => 'Canada', 'GB' => 'United Kingdom', 'AU' => 'Australia',
            'IN' => 'India', 'DE' => 'Germany', 'FR' => 'France', 'JP' => 'Japan',
            'BR' => 'Brazil', 'CN' => 'China', 'BD' => 'Bangladesh', 'ES' => 'Spain',
            'AF' => 'Afghanistan', 'AL' => 'Albania', 'DZ' => 'Algeria', 'AS' => 'American Samoa',
            'AD' => 'Andorra', 'AO' => 'Angola', 'AI' => 'Anguilla', 'AQ' => 'Antarctica',
            'AG' => 'Antigua and Barbuda', 'AR' => 'Argentina', 'AM' => 'Armenia', 'AW' => 'Aruba',
            'AT' => 'Austria', 'AZ' => 'Azerbaijan', 'BS' => 'Bahamas', 'BH' => 'Bahrain',
            'BB' => 'Barbados', 'BY' => 'Belarus', 'BE' => 'Belgium', 'BZ' => 'Belize',
            'BJ' => 'Benin', 'BM' => 'Bermuda', 'BT' => 'Bhutan', 'BO' => 'Bolivia',
            'BA' => 'Bosnia and Herzegovina', 'BW' => 'Botswana', 'BV' => 'Bouvet Island',
            'IO' => 'British Indian Ocean Territory', 'VG' => 'British Virgin Islands', 'BN' => 'Brunei',
            'BG' => 'Bulgaria', 'BF' => 'Burkina Faso', 'BI' => 'Burundi', 'KH' => 'Cambodia',
            'CM' => 'Cameroon', 'KY' => 'Cayman Islands', 'CF' => 'Central African Republic',
            'TD' => 'Chad', 'CL' => 'Chile', 'CX' => 'Christmas Island', 'CC' => 'Cocos (Keeling) Islands',
            'CO' => 'Colombia', 'KM' => 'Comoros', 'CK' => 'Cook Islands', 'CR' => 'Costa Rica',
            'HR' => 'Croatia', 'CU' => 'Cuba', 'CY' => 'Cyprus', 'CZ' => 'Czechia',
            'CD' => 'DR Congo', 'DK' => 'Denmark', 'DJ' => 'Djibouti', 'DM' => 'Dominica',
            'DO' => 'Dominican Republic', 'TL' => 'East Timor', 'EC' => 'Ecuador', 'EG' => 'Egypt',
            'SV' => 'El Salvador', 'GQ' => 'Equatorial Guinea', 'ER' => 'Eritrea', 'EE' => 'Estonia',
            'ET' => 'Ethiopia', 'FK' => 'Falkland Islands', 'FO' => 'Faroe Islands', 'FJ' => 'Fiji',
            'FI' => 'Finland', 'GF' => 'French Guiana', 'PF' => 'French Polynesia', 'GA' => 'Gabon',
            'GM' => 'Gambia', 'GE' => 'Georgia', 'GH' => 'Ghana', 'GI' => 'Gibraltar',
            'GR' => 'Greece', 'GL' => 'Greenland', 'GD' => 'Grenada', 'GP' => 'Guadeloupe',
            'GU' => 'Guam', 'GT' => 'Guatemala', 'GN' => 'Guinea', 'GW' => 'Guinea-Bissau',
            'GY' => 'Guyana', 'HT' => 'Haiti', 'HM' => 'Heard Island and McDonald Islands',
            'HN' => 'Honduras', 'HU' => 'Hungary', 'IS' => 'Iceland', 'ID' => 'Indonesia',
            'IR' => 'Iran', 'IQ' => 'Iraq', 'IE' => 'Ireland', 'IL' => 'Israel',
            'IT' => 'Italy', 'CI' => 'Ivory Coast', 'JM' => 'Jamaica', 'JO' => 'Jordan',
            'KZ' => 'Kazakhstan', 'KE' => 'Kenya', 'KI' => 'Kiribati', 'KW' => 'Kuwait',
            'KG' => 'Kyrgyzstan', 'LA' => 'Laos', 'LV' => 'Latvia', 'LB' => 'Lebanon',
            'LS' => 'Lesotho', 'LR' => 'Liberia', 'LY' => 'Libya', 'LI' => 'Liechtenstein',
            'LT' => 'Lithuania', 'LU' => 'Luxembourg', 'MO' => 'Macao', 'MG' => 'Madagascar',
            'MW' => 'Malawi', 'MY' => 'Malaysia', 'MV' => 'Maldives', 'ML' => 'Mali',
            'MT' => 'Malta', 'MQ' => 'Martinique', 'MR' => 'Mauritania', 'MU' => 'Mauritius',
            'YT' => 'Mayotte', 'MX' => 'Mexico', 'FM' => 'Micronesia', 'MD' => 'Moldova',
            'MC' => 'Monaco', 'MN' => 'Mongolia', 'ME' => 'Montenegro', 'MS' => 'Montserrat',
            'MA' => 'Morocco', 'MZ' => 'Mozambique', 'MM' => 'Myanmar', 'NA' => 'Namibia',
            'NR' => 'Nauru', 'NP' => 'Nepal', 'NL' => 'Netherlands', 'NC' => 'New Caledonia',
            'NZ' => 'New Zealand', 'NI' => 'Nicaragua', 'NE' => 'Niger', 'NG' => 'Nigeria',
            'NU' => 'Niue', 'NF' => 'Norfolk Island', 'KP' => 'North Korea', 'MP' => 'Northern Mariana Islands',
            'NO' => 'Norway', 'OM' => 'Oman', 'PK' => 'Pakistan', 'PW' => 'Palau',
            'PS' => 'Palestine', 'PA' => 'Panama', 'PG' => 'Papua New Guinea', 'PY' => 'Paraguay',
            'PE' => 'Peru', 'PH' => 'Philippines', 'PN' => 'Pitcairn Islands', 'PL' => 'Poland',
            'PT' => 'Portugal', 'PR' => 'Puerto Rico', 'QA' => 'Qatar', 'CG' => 'Republic of the Congo',
            'RO' => 'Romania', 'RU' => 'Russia', 'RW' => 'Rwanda', 'SH' => 'Saint Helena',
            'KN' => 'Saint Kitts and Nevis', 'LC' => 'Saint Lucia', 'PM' => 'Saint Pierre and Miquelon',
            'VC' => 'Saint Vincent and the Grenadines', 'WS' => 'Samoa', 'SM' => 'San Marino',
            'ST' => 'Sao Tome and Principe', 'SA' => 'Saudi Arabia', 'SN' => 'Senegal', 'RS' => 'Serbia',
            'SC' => 'Seychelles', 'SL' => 'Sierra Leone', 'SG' => 'Singapore', 'SK' => 'Slovakia',
            'SI' => 'Slovenia', 'SB' => 'Solomon Islands', 'SO' => 'Somalia', 'ZA' => 'South Africa',
            'KR' => 'South Korea', 'SS' => 'South Sudan', 'LK' => 'Sri Lanka', 'SD' => 'Sudan',
            'SR' => 'Suriname', 'SJ' => 'Svalbard and Jan Mayen', 'SE' => 'Sweden', 'CH' => 'Switzerland',
            'SY' => 'Syria', 'TW' => 'Taiwan', 'TJ' => 'Tajikistan', 'TZ' => 'Tanzania',
            'TH' => 'Thailand', 'TG' => 'Togo', 'TK' => 'Tokelau', 'TO' => 'Tonga',
            'TT' => 'Trinidad and Tobago', 'TN' => 'Tunisia', 'TR' => 'Turkey', 'TM' => 'Turkmenistan',
            'TC' => 'Turks and Caicos Islands', 'TV' => 'Tuvalu', 'UG' => 'Uganda', 'UA' => 'Ukraine',
            'AE' => 'United Arab Emirates', 'UY' => 'Uruguay', 'UZ' => 'Uzbekistan', 'VU' => 'Vanuatu',
            'VA' => 'Vatican City', 'VE' => 'Venezuela', 'VN' => 'Vietnam', 'WF' => 'Wallis and Futuna',
            'EH' => 'Western Sahara', 'YE' => 'Yemen', 'ZM' => 'Zambia', 'ZW' => 'Zimbabwe',
        ];
        return view('frontend.checkout.checkout', $data);
    }

    public function storeDetails(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:50',
            'last_name' => 'required|string|max:50',
            'email' => 'required|email',
            'country' => 'required|string|max:2'
        ]);
        Session::put('billing_details', $validated);
        return redirect()->route('checkout.payment');
    }

    public function payment()
    {
        $data = $this->getCheckoutData();
        $billing = Session::get('billing_details');
        if (empty($data['cart'])) {
            return redirect()->route('checkout.cart')->with('error', 'Your cart is empty.');
        }
        if (empty($billing)) {
            return redirect()->route('checkout.details')->with('error', 'Please confirm your billing details.');
        }
        if (empty($data['enabledGateways'])) {
            return redirect()->route('checkout.details')->with('error', 'No payment gateways are currently configured.');
        }
        $data['billing'] = $billing;
        return view('frontend.checkout.payment', $data);
    }

    public function process(Request $request)
    {
        $request->validate(['gateway' => 'required|string']);
        $data = $this->getCheckoutData();
        $gateway = $request->gateway;
        $enabledGateways = $data['enabledGateways'];
        if (!array_key_exists($gateway, $enabledGateways)) {
            return redirect()->route('checkout.payment')->with('error', 'Invalid payment method selected.');
        }
        $orderId = 'ORD-' . strtoupper(Str::random(9));
        Session::put('processing_order_id', $orderId);
        try {
            if ($gateway === 'stripe') {
                $request->validate(['stripeToken' => 'required']);
                Stripe::setApiKey($enabledGateways['stripe']['secret_key']);
                $charge = Charge::create([
                    'amount' => round($data['total'] * 100),
                    'currency' => strtolower($data['settings']['currency_code'] ?? 'usd'),
                    'source' => $request->stripeToken,
                    'description' => 'Order ' . $orderId,
                    'metadata' => ['order_id' => $orderId]
                ]);
                if ($charge->status === 'succeeded') {
                    $this->finalizeOrder($orderId, 'success', 'stripe', $charge->id, []);
                    return $this->redirectSuccess($orderId, $data['total'], 'success', 'stripe', $charge->id);
                } else {
                    throw new \Exception('Stripe payment was not successful.');
                }
            } elseif ($gateway === 'offline') {
                $request->validate(['offline_transaction_id' => 'required|string']);
                $userRefId = $request->input('offline_transaction_id');
                $gatewayResponse = ['user_trx_ref' => $userRefId];
                $this->finalizeOrder($orderId, 'pending', 'offline', $userRefId, $gatewayResponse);
                return $this->redirectSuccess($orderId, $data['total'], 'pending', 'offline', $userRefId);
            } elseif ($gateway === 'paypal') {
                $config = $enabledGateways['paypal'];
                $baseUrl = ($config['mode'] === 'live') ? 'https://api-m.paypal.com' : 'https://api-m.sandbox.paypal.com';
                $response = Http::withBasicAuth($config['client_id'], $config['secret'])
                    ->asForm()->post($baseUrl . '/v1/oauth2/token', ['grant_type' => 'client_credentials']);
                if (!$response->successful()) {
                    throw new \Exception('PayPal Connection Failed. Please check API credentials.');
                }
                $accessToken = $response->json()['access_token'];
                $orderResponse = Http::withToken($accessToken)->post($baseUrl . '/v2/checkout/orders', [
                    'intent' => 'CAPTURE',
                    'purchase_units' => [[
                        'reference_id' => $orderId,
                        'amount' => ['currency_code' => $data['settings']['currency_code'] ?? 'USD', 'value' => number_format($data['total'], 2, '.', '')]
                    ]],
                    'application_context' => [
                        'return_url' => route('checkout.success'),
                        'cancel_url' => route('checkout.payment'),
                        'user_action' => 'PAY_NOW'
                    ]
                ]);
                if (!$orderResponse->successful()) {
                    throw new \Exception('Failed to generate PayPal order.');
                }
                foreach ($orderResponse->json()['links'] as $link) {
                    if ($link['rel'] === 'approve') {
                        return redirect()->away($link['href']);
                    }
                }
                throw new \Exception('PayPal approval link not found.');
            } elseif ($gateway === 'razorpay') {
                $request->validate(['razorpay_payment_id' => 'required']);
                $paymentId = $request->input('razorpay_payment_id');
                $keyId = $enabledGateways['razorpay']['key_id'];
                $keySecret = $enabledGateways['razorpay']['key_secret'];
                $verify = Http::withBasicAuth($keyId, $keySecret)->get("https://api.razorpay.com/v1/payments/{$paymentId}");
                if ($verify->successful() && in_array($verify->json()['status'], ['authorized', 'captured'])) {
                    if ($verify->json()['status'] === 'authorized') {
                        Http::withBasicAuth($keyId, $keySecret)
                            ->post("https://api.razorpay.com/v1/payments/{$paymentId}/capture", [
                                'amount' => round($data['total'] * 100),
                                'currency' => $data['settings']['currency_code'] ?? 'USD'
                            ]);
                    }
                    $this->finalizeOrder($orderId, 'success', 'razorpay', $paymentId, $verify->json());
                    return $this->redirectSuccess($orderId, $data['total'], 'success', 'razorpay', $paymentId);
                } else {
                    throw new \Exception('Razorpay payment verification failed.');
                }
            }
            
            $addonActive = Schema::hasTable('addons') && DB::table('addons')->where('slug', 'payment-gateways')->where('is_active', 1)->exists();
            if ($addonActive && in_array($gateway, ['payu', 'paystack', 'flutterwave', 'mollie', 'paddle'])) {
                $paymentData = [
                    'amount' => $data['total'],
                    'currency' => $data['settings']['currency_code'] ?? 'USD',
                    'transaction_id' => $orderId,
                    'email' => Auth::user()->email ?? 'customer@example.com',
                    'name' => Auth::user()->name ?? 'Customer',
                    'callback_url' => route('checkout.addon.callback', ['gateway' => $gateway]),
                ];

                if ($gateway === 'paystack') return (new \Addons\PaymentGateways\Services\Gateways\PaystackService())->initializePayment($paymentData);
                if ($gateway === 'flutterwave') return (new \Addons\PaymentGateways\Services\Gateways\FlutterwaveService())->initializePayment($paymentData);
                if ($gateway === 'mollie') return (new \Addons\PaymentGateways\Services\Gateways\MollieService())->initializePayment($paymentData);
                if ($gateway === 'paddle') return (new \Addons\PaymentGateways\Services\Gateways\PaddleService())->initializePayment($paymentData);
                if ($gateway === 'payu') return (new \Addons\PaymentGateways\Services\Gateways\PayUService())->initializePayment($paymentData);
            }

        } catch (\Exception $e) {
            return redirect()->route('checkout.payment')->with('error', $e->getMessage());
        }
        return redirect()->route('checkout.payment')->with('error', 'An unknown error occurred during payment processing.');
    }
    
    public function success(Request $request)
    {
        if ($request->input('gateway') === 'paypal' && $request->has('token')) {
            $data = $this->getCheckoutData();
            $config = $data['enabledGateways']['paypal'];
            $baseUrl = ($config['mode'] === 'live') ? 'https://api-m.paypal.com' : 'https://api-m.sandbox.paypal.com';
            $paypalToken = $request->input('token');
            $orderId = Session::get('processing_order_id');
            try {
                $tokenResponse = Http::withBasicAuth($config['client_id'], $config['secret'])
                    ->asForm()->post($baseUrl . '/v1/oauth2/token', ['grant_type' => 'client_credentials']);
                $accessToken = $tokenResponse->json()['access_token'];
                $captureResponse = Http::withToken($accessToken)
                    ->post($baseUrl . "/v2/checkout/orders/{$paypalToken}/capture", [], ['Content-Type' => 'application/json']);
                if ($captureResponse->successful()) {
                    $paypalTxId = $captureResponse->json()['purchase_units'][0]['payments']['captures'][0]['id'] ?? 'PAYPAL-NA';
                    $this->finalizeOrder($orderId, 'success', 'paypal', $paypalTxId, $captureResponse->json());
                    return $this->redirectSuccess($orderId, $data['total'], 'success', 'paypal', $paypalTxId);
                } else {
                    throw new \Exception('PayPal capture failed.');
                }
            } catch (\Exception $e) {
                return redirect()->route('checkout.payment')->with('error', 'PayPal Error: ' . $e->getMessage());
            }
        }
        $data = $this->getCheckoutData();
        $data['order_id'] = Session::get('order_id');
        $data['paid_amount'] = Session::get('paid_amount');
        $data['payment_status'] = Session::get('payment_status');
        $data['gateway'] = Session::get('gateway');
        $data['transaction_ref'] = Session::get('transaction_ref');
        if (!$data['order_id']) {
            return redirect()->route('home');
        }
        return view('frontend.checkout.success', $data);
    }

    public function addonCallback(Request $request, $gateway)
    {
        $isVerified = false;
        $service = null;

        if ($gateway === 'paystack') $service = new \Addons\PaymentGateways\Services\Gateways\PaystackService();
        elseif ($gateway === 'flutterwave') $service = new \Addons\PaymentGateways\Services\Gateways\FlutterwaveService();
        elseif ($gateway === 'mollie') $service = new \Addons\PaymentGateways\Services\Gateways\MollieService();
        elseif ($gateway === 'paddle') $service = new \Addons\PaymentGateways\Services\Gateways\PaddleService();
        elseif ($gateway === 'payu') $service = new \Addons\PaymentGateways\Services\Gateways\PayUService();

        if ($service) {
            $isVerified = $service->verifyPayment($request->all());
        }

        if ($isVerified) {
            $orderId = Session::get('processing_order_id');
            $data = $this->getCheckoutData();
            $amount = $data['total'] ?? 0;

            $transactionRef = 'TXN-' . strtoupper(Str::random(9));
            if ($gateway === 'paystack') $transactionRef = $request->input('reference');
            if ($gateway === 'flutterwave') $transactionRef = $request->input('transaction_id');
            if ($gateway === 'mollie') $transactionRef = $request->input('id');
            if ($gateway === 'payu') $transactionRef = $request->input('txnid');
            if ($gateway === 'paddle') {
                $transactionRef = $request->input('p_order_id') ?? $request->input('checkout_id');
            }

            $this->finalizeOrder($orderId, 'success', $gateway, $transactionRef, $request->all());
            return $this->redirectSuccess($orderId, $amount, 'success', $gateway, $transactionRef);
        }

        return redirect()->route('checkout.payment')->with('error', 'Payment verification failed or was cancelled.');
    }
    
    private function finalizeOrder($orderId, $status, $gateway, $gatewayTrxId, array $gatewayResponse)
    {
        DB::transaction(function () use ($orderId, $status, $gateway, $gatewayTrxId, $gatewayResponse) {
            $data = $this->getCheckoutData();
            $cart = $data['cart'];
            $user = Auth::user();
            $isSuccess = in_array($status, ['success', 'approved', 'paid', 'successful']);
            $pivotStatus = $isSuccess ? 'active' : 'pending';
            $payment = Payment::create([
                'user_id' => $user->id,
                'transaction_id' => $orderId,
                'gateway' => $gateway,
                'amount' => $data['total'],
                'currency' => $data['settings']['currency_code'] ?? 'USD',
                'status' => $status,
                'gateway_response' => json_encode($gatewayResponse),
            ]);
            foreach ($cart as $item) {
                if ($item['type'] === 'plan') {
                    $planPeriod = $item['plan_period'] ?? Session::get('plan_period', 'monthly');
                    $paymentData = [
                        'type' => 'subscription',
                        'plan_id' => $item['id'],
                        'start_date' => now(),
                        'end_date' => ($planPeriod === 'yearly') ? now()->addYear() : now()->addMonth(),
                    ];
                    if ($isSuccess) {
                        $user->update([
                            'plan_id' => $item['id'],
                            'is_subscribed' => true,
                        ]);
                    }
                    $payment->update($paymentData);
                } elseif ($item['type'] === 'exam') {
                    $pivotData = [
                        'price' => $item['price'],
                        'status' => $pivotStatus,
                        'payment_method' => $gateway,
                        'transaction_id' => $orderId,
                    ];
                    if ($user->exams()->where('exam_id', $item['id'])->exists()) {
                        $user->exams()->updateExistingPivot($item['id'], $pivotData);
                    } else {
                        $user->exams()->attach($item['id'], $pivotData);
                    }
                } elseif ($item['type'] === 'ielts') {
                    if (Schema::hasTable('ielts_test_user')) {
                        if ($isSuccess) {
                            DB::table('ielts_test_user')
                                ->where('user_id', $user->id)
                                ->where('ielts_test_id', $item['id'])
                                ->where('status', 'active')
                                ->update(['status' => 'completed', 'updated_at' => now()]);
                        }
                        DB::table('ielts_test_user')->insert([
                            'user_id' => $user->id,
                            'ielts_test_id' => $item['id'],
                            'price' => $item['price'],
                            'status' => $pivotStatus,
                            'transaction_id' => $orderId,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                } elseif ($item['type'] === 'course') {
                    if (Schema::hasTable('lms_enrollments')) {
                        DB::table('lms_enrollments')->updateOrInsert(
                            ['user_id' => $user->id, 'course_id' => $item['id']],
                            [
                                'status' => $pivotStatus,
                                'created_at' => now(),
                                'updated_at' => now()
                            ]
                        );
                    }
                }
            }
        });
    }

    public function syncPaymentToPivotRecords(Payment $payment)
    {
        $userId = $payment->user_id;
        $transactionId = $payment->transaction_id;

        if (Schema::hasTable('exam_user')) {
            DB::table('exam_user')
                ->where('transaction_id', $transactionId)
                ->where('status', 'pending')
                ->update([
                    'status' => 'active',
                    'updated_at' => now()
                ]);
        }

        if (Schema::hasTable('ielts_test_user')) {
            DB::table('ielts_test_user')
                ->where('transaction_id', $transactionId)
                ->where('status', 'pending')
                ->update([
                    'status' => 'active',
                    'updated_at' => now()
                ]);
        }
        
        if (Schema::hasTable('lms_enrollments')) {
            DB::table('lms_enrollments')
                ->where('user_id', $userId)
                ->where('status', 'pending')
                ->update([
                    'status' => 'active',
                    'updated_at' => now()
                ]);
        }

        if ($payment->plan_id) {
            $user = User::find($userId);
            if ($user) {
                $user->update([
                    'plan_id' => $payment->plan_id,
                    'is_subscribed' => true
                ]);
            }
        }
    }

    private function redirectSuccess($orderId, $amount, $status, $gateway, $transactionRef)
    {
        Session::forget(['cart', 'billing_details', 'plan_period', 'processing_order_id']);
        Session::flash('order_id', $orderId);
        Session::flash('paid_amount', $amount);
        Session::flash('payment_status', $status);
        Session::flash('gateway', $gateway);
        Session::flash('transaction_ref', $transactionRef);
        return redirect()->route('checkout.success');
    }
}