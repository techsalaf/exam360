<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use App\Models\User;
use App\Models\Category;
use App\Models\SystemSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class PlanController extends Controller
{
    public function index(Request $request)
    {
        $query = Plan::with('categories');

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $plans = $query->latest()->paginate(10);
        $categories = Category::orderBy('name')->get();

        $settings = SystemSetting::getSettings();
        $currencySymbol = $settings['currency_symbol'] ?? '$';
        
        $totalPlans = Plan::count();
        $activePlans = Plan::where('is_active', true)->count();
        $highestPrice = Plan::max('price_monthly') ?? 0;

        $users = User::where('is_banned', false)
            ->select('id', 'name', 'email')
            ->orderBy('name')
            ->get();

        $kpis = [
            ['value' => $totalPlans, 'label' => 'Total Plans', 'icon' => 'fa-solid fa-tags', 'color' => 'primary'],
            ['value' => $activePlans, 'label' => 'Active Plans', 'icon' => 'fa-regular fa-circle-check', 'color' => 'success'],
            ['value' => $currencySymbol . number_format($highestPrice, 2), 'label' => 'Highest Monthly Price', 'icon' => 'fa-solid fa-dollar-sign', 'color' => 'warning'],
        ];

        return view('admin.plans.index', compact('plans', 'kpis', 'currencySymbol', 'users', 'categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'              => 'required|string|max:255|unique:plans,name',
            'price_monthly'     => 'required|numeric|min:0',
            'price_yearly'      => 'required|numeric|min:0',
            'limit_monthly'     => 'required|integer|min:0',
            'limit_yearly'      => 'required|integer|min:0',
            'short_description' => 'nullable|string|max:255',
            'category_ids'      => 'nullable|array',
            'category_ids.*'    => 'exists:categories,id'
        ]);

        DB::beginTransaction();
        try {
            $plan = Plan::create($request->except('category_ids'));
            if ($request->has('category_ids')) {
                $plan->categories()->sync($request->category_ids);
            }
            DB::commit();
            return redirect()->back()->with('success', 'Plan created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Plan Creation Failed: " . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to create plan.');
        }
    }

    public function update(Request $request, $id)
    {
        $plan = Plan::findOrFail($id);

        $request->validate([
            'name'              => 'required|string|max:255|unique:plans,name,' . $plan->id,
            'price_monthly'     => 'required|numeric|min:0',
            'price_yearly'      => 'required|numeric|min:0',
            'limit_monthly'     => 'required|integer|min:0',
            'limit_yearly'      => 'required|integer|min:0',
            'short_description' => 'nullable|string|max:255',
            'category_ids'      => 'nullable|array',
            'category_ids.*'    => 'exists:categories,id'
        ]);

        DB::beginTransaction();
        try {
            $plan->update($request->except('category_ids'));
            $plan->categories()->sync($request->category_ids ?? []);
            DB::commit();
            return redirect()->back()->with('success', 'Plan updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Plan Update Failed: " . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to update plan.');
        }
    }

    public function assignUser(Request $request, $id)
    {
        $plan = Plan::findOrFail($id);

        $request->validate([
            'user_id'       => 'required|exists:users,id',
            'billing_cycle' => 'required|in:monthly,yearly',
        ]);

        $user = User::findOrFail($request->user_id);

        $user->update([
            'plan_id'         => $plan->id,
            'plan_type'       => $request->billing_cycle,
            'plan_expires_at' => $request->billing_cycle === 'yearly' ? now()->addYear() : now()->addMonth(),
        ]);

        return redirect()->back()->with('success', 'Plan assigned successfully.');
    }

    public function toggleStatus($id)
    {
        $plan = Plan::findOrFail($id);
        $plan->update(['is_active' => !$plan->is_active]);
        return redirect()->back()->with('success', 'Plan status updated.');
    }

    public function destroy($id)
    {
        try {
            Plan::findOrFail($id)->delete();
            return redirect()->back()->with('success', 'Plan deleted successfully.');
        } catch (\Exception $e) {
            Log::error("Plan Deletion Failed: " . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to delete plan.');
        }
    }
}