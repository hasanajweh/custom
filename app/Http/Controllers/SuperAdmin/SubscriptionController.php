<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use App\Models\Plan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class SubscriptionController extends Controller
{
    public function index(Request $request)
    {
        // Check if columns exist
        $hasEndsAt = Schema::hasColumn('subscriptions', 'ends_at');
        $hasStatus = Schema::hasColumn('subscriptions', 'status');

        // Get stats with column checks
        $activeCount = $hasStatus ? Subscription::where('status', 'active')->count() : 0;
        $cancelledCount = $hasStatus ? Subscription::where('status', 'cancelled')->count() : 0;

        // Only check expiring if ends_at column exists
        $expiringSoonCount = 0;
        if ($hasEndsAt && $hasStatus) {
            $expiringSoonCount = Subscription::where('status', 'active')
                ->whereNotNull('ends_at')
                ->where('ends_at', '<=', now()->addDays(30))
                ->count();
        }

        $totalCount = Subscription::count();

        // Calculate total revenue
        $totalRevenue = Subscription::query()
            ->when($hasStatus, function($query) {
                $query->where('subscriptions.status', 'active');
            })
            ->join('plans', 'subscriptions.plan_id', '=', 'plans.id')
            ->sum('plans.price_monthly');

        // Get all plans for filter
        $plans = Plan::all();

        // Build query
        $query = Subscription::with(['school', 'plan']);

        // Search filter
        if ($request->filled('search')) {
            $query->whereHas('school', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            });
        }

        // Status filter (only if column exists)
        if ($hasStatus && $request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Plan filter
        if ($request->filled('plan')) {
            $query->where('plan_id', $request->plan);
        }

        $subscriptions = $query->latest()->paginate(20);

        return view('superadmin.subscriptions.index', compact(
            'subscriptions',
            'plans',
            'activeCount',
            'cancelledCount',
            'expiringSoonCount',
            'totalCount',
            'totalRevenue'
        ));
    }

    public function activate(Subscription $subscription)
    {
        // Can activate if pending or paused
        if (!in_array($subscription->status, ['pending', 'paused'])) {
            return back()->with('error', 'Only pending or paused subscriptions can be activated.');
        }

        DB::beginTransaction();
        try {
            // Activate the subscription
            $subscription->update([
                'status' => 'active',
                'starts_at' => now(),
                'ends_at' => now()->addYear(), // or addMonth() based on plan
            ]);

            // Activate the school
            $subscription->school->update([
                'is_active' => true
            ]);

            DB::commit();

            return redirect()->route('superadmin.subscriptions.index')
                ->with('success', "Subscription activated for {$subscription->school->name}!");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error activating subscription: ' . $e->getMessage());
        }
    }

    public function edit(Subscription $subscription)
    {
        $plans = Plan::where('is_active', true)->get();

        // Set default dates if they don't exist
        if (!$subscription->starts_at) {
            $subscription->starts_at = now();
        }
        if (!$subscription->ends_at) {
            $subscription->ends_at = now()->addYear();
        }

        return view('superadmin.subscriptions.edit', compact('subscription', 'plans'));
    }

    public function update(Request $request, Subscription $subscription)
    {
        $rules = [
            'plan_id' => 'required|exists:plans,id',
        ];

        // Only validate these fields if columns exist
        if (Schema::hasColumn('subscriptions', 'status')) {
            $rules['status'] = 'required|in:active,paused,cancelled,pending';
        }
        if (Schema::hasColumn('subscriptions', 'starts_at')) {
            $rules['starts_at'] = 'required|date';
        }
        if (Schema::hasColumn('subscriptions', 'ends_at')) {
            $rules['ends_at'] = 'required|date|after:starts_at';
        }

        $validated = $request->validate($rules);

        DB::beginTransaction();
        try {
            $subscription->update($validated);

            // If status changed to active, activate school
            if (isset($validated['status']) && $validated['status'] === 'active') {
                $subscription->school->update(['is_active' => true]);
            }

            DB::commit();

            return redirect()->route('superadmin.subscriptions.index')
                ->with('success', 'Subscription updated successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error updating subscription: ' . $e->getMessage());
        }
    }

    public function pause(Subscription $subscription)
    {
        if (Schema::hasColumn('subscriptions', 'status')) {
            DB::beginTransaction();
            try {
                $subscription->update(['status' => 'paused']);
                DB::commit();

                return back()->with('success', 'Subscription paused successfully.');
            } catch (\Exception $e) {
                DB::rollBack();
                return back()->with('error', 'Error pausing subscription: ' . $e->getMessage());
            }
        }

        return back()->with('error', 'Status column not available.');
    }

    public function resume(Subscription $subscription)
    {
        if (Schema::hasColumn('subscriptions', 'status')) {
            DB::beginTransaction();
            try {
                $subscription->update(['status' => 'active']);
                $subscription->school->update(['is_active' => true]);
                DB::commit();

                return back()->with('success', 'Subscription resumed successfully.');
            } catch (\Exception $e) {
                DB::rollBack();
                return back()->with('error', 'Error resuming subscription: ' . $e->getMessage());
            }
        }

        return back()->with('error', 'Status column not available.');
    }
}
