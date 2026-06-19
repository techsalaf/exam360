<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Plan;
use App\Models\Notification;
use App\Models\Ticket;
use App\Models\SystemSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role as SpatieRole;
use Spatie\Permission\PermissionRegistrar;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view_users')->only(['index', 'show']);
        $this->middleware('permission:create_users')->only(['store']);
        $this->middleware('permission:change_roles')->only(['update', 'toggleBan', 'verifyEmail', 'verifyMobile', 'assignPlan']);
    }

    public function index(Request $request, $status = null)
    {
        $query = User::with(['roles', 'plan'])->latest();

        if (!Auth::user()->hasRole('Super Admin')) {
            $query->whereDoesntHave('roles', function ($q) {
                $q->where('name', 'Super Admin');
            });
        } else {
            $query->where('id', '!=', Auth::id());
        }

        $filterStatus = $status ?? $request->get('status');
        $pageTitle = __('users.title_list');

        switch ($filterStatus) {
            case 'active':
                $query->where('is_banned', false);
                $pageTitle = __('users.title_active');
                break;
            case 'banned':
                $query->where('is_banned', true);
                $pageTitle = __('users.title_banned');
                break;
            case 'unverified_email':
                $query->whereNull('email_verified_at');
                $pageTitle = __('users.title_unverified_email');
                break;
            case 'unverified_mobile':
                $query->whereNull('mobile_verified_at');
                $pageTitle = __('users.title_unverified_mobile');
                break;
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('mobile', 'like', "%{$search}%");
            });
        }

        $users = $query->paginate(10);

        $kpis = [
            ['value' => number_format(User::count()), 'label' => __('users.kpi_total_users'), 'icon' => 'fa-solid fa-users', 'color' => 'primary'],
            ['value' => number_format(User::whereNull('email_verified_at')->count()), 'label' => __('users.kpi_unverified_emails'), 'icon' => 'fa-solid fa-envelope-open-text', 'color' => 'warning'],
            ['value' => number_format(User::where('is_banned', true)->count()), 'label' => __('users.kpi_banned_users'), 'icon' => 'fa-solid fa-user-slash', 'color' => 'danger'],
        ];

        $allRoles = SpatieRole::all();
        $plans = Plan::where('is_active', true)->get();

        return view('admin.users.index', compact('users', 'pageTitle', 'filterStatus', 'kpis', 'allRoles', 'plans'));
    }

    public function assignPlan(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        $request->validate([
            'plan_id'       => 'required|exists:plans,id',
            'billing_cycle' => 'required|in:monthly,yearly',
        ]);

        $plan = Plan::findOrFail($request->plan_id);
        
        $user->update([
            'plan_id'         => $plan->id,
            'plan_type'       => $request->billing_cycle,
            'plan_expires_at' => $request->billing_cycle === 'yearly' ? now()->addYear() : now()->addMonth(),
        ]);

        return back()->with('success', __('users.msg_plan_assigned'));
    }

    public function show($id)
    {
        $user = User::with(['roles', 'plan'])->findOrFail($id);
        $settings = SystemSetting::getSettings();
        $currencySymbol = $settings['currency_symbol'] ?? '$';
        $totalTransactionAmount = $user->payments()->where('status', 'success')->sum('amount');

        $kpis = [
            ['value' => $currencySymbol . number_format($totalTransactionAmount, 2), 'label' => __('users.kpi_total_transactions'), 'icon' => 'fa-solid fa-money-bill-wave', 'color' => 'primary'],
            ['value' => $user->examSessions()->count(), 'label' => __('users.kpi_exams_taken'), 'icon' => 'fa-solid fa-file-signature', 'color' => 'neutral'],
            ['value' => $user->payments()->count(), 'label' => __('users.kpi_payment_count'), 'icon' => 'fa-solid fa-receipt', 'color' => 'neutral'],
        ];

        $notifications = $user->notifications()->latest()->limit(10)->get();
        $loginLogs = $user->loginLogs()->latest()->limit(10)->get();
        $paymentHistory = $user->payments()->latest()->limit(5)->get();
        $supportTickets = Ticket::where('user_id', $user->id)->latest()->limit(5)->get();
        $openTicketsCount = Ticket::where('user_id', $user->id)->where('status', 'open')->count();
        $allRoles = SpatieRole::all();
        $plans = Plan::where('is_active', true)->get();
        
        $registrationFieldsJson = SystemSetting::where('key', 'registration_custom_fields')->value('value');
        $fieldDefinitions = $registrationFieldsJson ? json_decode($registrationFieldsJson, true) : [];

        return view('admin.users.show', compact('user', 'kpis', 'notifications', 'loginLogs', 'paymentHistory', 'supportTickets', 'openTicketsCount', 'allRoles', 'currencySymbol', 'plans', 'fieldDefinitions'));
    }

    public function store(Request $request)
    {
        $rules = [
            'name'      => 'required|string|max:255',
            'email'     => 'required|string|email|max:255|unique:users',
            'password'  => 'required|string|min:8',
            'role_name' => ['required', 'string', Rule::exists('roles', 'name')],
            'mobile'    => 'nullable|string|max:20',
        ];

        $registrationFieldsJson = SystemSetting::where('key', 'registration_custom_fields')->value('value');
        $customFields = $registrationFieldsJson ? json_decode($registrationFieldsJson, true) : [];
        
        foreach ($customFields as $field) {
            $fieldKey = 'custom_' . str_replace(' ', '_', strtolower($field['label']));
            $rules[$fieldKey] = ($field['required'] == '1' ? 'required' : 'nullable') . ($field['type'] === 'attachment' ? '|file|max:5120' : '|string');
        }

        $request->validate($rules);

        if ($request->role_name === 'Super Admin' && Auth::id() !== 1) {
            return back()->with('error', __('users.msg_unauthorized_role'));
        }

        $customData = [];
        foreach ($customFields as $field) {
            $fieldKey = 'custom_' . str_replace(' ', '_', strtolower($field['label']));
            if ($request->hasFile($fieldKey)) {
                $customData[$field['label']] = $request->file($fieldKey)->store('user_attachments', 'public');
            } else {
                $customData[$field['label']] = $request->input($fieldKey);
            }
        }

        $user = User::create([
            'name'              => $request->name,
            'email'             => $request->email,
            'password'          => Hash::make($request->password),
            'mobile'            => $request->mobile,
            'email_verified_at' => now(),
            'is_banned'         => false,
            'custom_fields'     => $customData,
        ]);

        $user->assignRole($request->role_name);
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        return back()->with('success', __('users.msg_user_created'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        if ($user->hasRole('Super Admin') && !Auth::user()->hasRole('Super Admin')) {
            return back()->with('error', __('users.msg_unauthorized_action'));
        }

        $rules = [
            'name'      => 'required|string|max:255',
            'email'     => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'mobile'    => 'nullable|string|max:20',
            'password'  => 'nullable|string|min:8',
            'role_name' => ['required', 'string', Rule::exists('roles', 'name')],
        ];

        $registrationFieldsJson = SystemSetting::where('key', 'registration_custom_fields')->value('value');
        $customFields = $registrationFieldsJson ? json_decode($registrationFieldsJson, true) : [];
        
        foreach ($customFields as $field) {
            $fieldKey = 'custom_' . str_replace(' ', '_', strtolower($field['label']));
            $rules[$fieldKey] = ($field['required'] == '1' ? 'required' : 'nullable') . ($field['type'] === 'attachment' ? '|file|max:5120' : '|string');
        }

        $request->validate($rules);

        if ($request->role_name === 'Super Admin' && Auth::id() !== 1) {
            return back()->with('error', __('users.msg_unauthorized_role'));
        }

        $user->name = $request->name;
        $user->email = $request->email;
        $user->mobile = $request->mobile;
        
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $customData = $user->custom_fields ?? [];
        foreach ($customFields as $field) {
            $fieldKey = 'custom_' . str_replace(' ', '_', strtolower($field['label']));
            if ($request->hasFile($fieldKey)) {
                if (isset($customData[$field['label']])) Storage::disk('public')->delete($customData[$field['label']]);
                $customData[$field['label']] = $request->file($fieldKey)->store('user_attachments', 'public');
            } elseif ($request->has($fieldKey)) {
                $customData[$field['label']] = $request->input($fieldKey);
            }
        }
        $user->custom_fields = $customData;

        $user->save();
        $user->syncRoles([$request->role_name]);
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        return back()->with('success', __('users.msg_user_updated'));
    }

    public function toggleBan($id)
    {
        $user = User::findOrFail($id);
        if ($user->id === Auth::id() || $user->hasRole('Super Admin')) {
            return back()->with('error', __('users.msg_cannot_ban'));
        }
        $user->update(['is_banned' => !$user->is_banned]);
        return back()->with('success', $user->is_banned ? __('users.msg_user_banned') : __('users.msg_user_activated'));
    }

    public function loginAsUser($id)
    {
        if (!Auth::user()->hasRole('Super Admin')) abort(403);
        $user = User::findOrFail($id);
        if ($user->hasRole('Super Admin')) return back()->with('error', __('users.msg_cannot_impersonate'));
        session()->put('impersonate_admin_id', Auth::id());
        Auth::login($user);
        return redirect()->route('user.dashboard');
    }

    public function stopImpersonation()
    {
        if (session()->has('impersonate_admin_id')) {
            Auth::loginUsingId(session('impersonate_admin_id'));
            session()->forget('impersonate_admin_id');
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route('user.dashboard');
    }

    public function verifyEmail($id)
    {
        $user = User::findOrFail($id);
        $user->update([
            'email_verified_at' => $user->email_verified_at ? null : now()
        ]);
        return back()->with('success', __('users.msg_user_updated'));
    }

    public function verifyMobile($id)
    {
        $user = User::findOrFail($id);
        $user->update([
            'mobile_verified_at' => $user->mobile_verified_at ? null : now()
        ]);
        return back()->with('success', __('users.msg_user_updated'));
    }

    public function notify(Request $request, $id)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        $user = User::findOrFail($id);

        Notification::create([
            'user_id' => $user->id,
            'subject' => $request->subject,
            'message' => $request->message,
            'is_important' => $request->has('is_important') ? true : false,
        ]);

        return back()->with('success', __('users.msg_user_updated'));
    }

    public function clearNotifications($id)
    {
        $user = User::findOrFail($id);
        $user->notifications()->delete();
        return back()->with('success', __('users.msg_user_updated'));
    }

    public function deleteNotification($id)
    {
        $notification = Notification::findOrFail($id);
        $notification->delete();
        return back()->with('success', __('users.msg_user_updated'));
    }
}