<?php
// app/Http/Controllers/SuperAdmin/PlanController.php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PlanController extends Controller
{
    public function index()
    {
        $plans = Plan::withCount('subscriptions')->latest()->get();

        return view('superadmin.plans.index', compact('plans'));
    }

    public function create()
    {
        return view('superadmin.plans.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:plans,slug',
            'price_monthly' => 'required|numeric|min:0',
            'price_annually' => 'required|numeric|min:0',
            'storage_limit_in_gb' => 'required|integer|min:1',
            'features' => 'nullable|array',
            'features.*' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        // Generate slug if not provided
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        // Convert prices to cents (stored as integers)
        $validated['price_monthly'] = $validated['price_monthly'] * 100;
        $validated['price_annually'] = $validated['price_annually'] * 100;

        // Filter out empty features
        if (isset($validated['features'])) {
            $validated['features'] = array_filter($validated['features']);
        }

        // Handle checkbox
        $validated['is_active'] = $request->has('is_active');

        Plan::create($validated);

        return redirect()->route('superadmin.plans.index')
            ->with('success', 'Plan created successfully.');
    }

    public function edit(Plan $plan)
    {
        return view('superadmin.plans.edit', compact('plan'));
    }

    public function update(Request $request, Plan $plan)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:plans,slug,' . $plan->id,
            'price_monthly' => 'required|numeric|min:0',
            'price_annually' => 'required|numeric|min:0',
            'storage_limit_in_gb' => 'required|integer|min:1',
            'features' => 'nullable|array',
            'features.*' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        // Convert prices to cents
        $validated['price_monthly'] = $validated['price_monthly'] * 100;
        $validated['price_annually'] = $validated['price_annually'] * 100;

        // Filter out empty features
        if (isset($validated['features'])) {
            $validated['features'] = array_filter($validated['features']);
        }

        // Handle checkbox
        $validated['is_active'] = $request->has('is_active');

        $plan->update($validated);

        return redirect()->route('superadmin.plans.index')
            ->with('success', 'Plan updated successfully.');
    }

    public function destroy(Plan $plan)
    {
        // Check if plan has active subscriptions
        if ($plan->subscriptions()->where('status', 'active')->exists()) {
            return back()->with('error', 'Cannot delete plan with active subscriptions.');
        }

        $plan->delete();

        return redirect()->route('superadmin.plans.index')
            ->with('success', 'Plan deleted successfully.');
    }
}
