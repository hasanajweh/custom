<?php
// app/Http/Controllers/SuperAdmin/DashboardController.php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\School;
use App\Models\Subscription;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DashboardController extends Controller
{
    public function index()
    {
        // Basic Stats
        $totalSchools = School::count();
        $activeSubscriptions = Subscription::where('status', 'active')->count();
        $activeSchools = School::where('is_active', true)->count();
        $totalUsers = User::whereNotNull('school_id')->count();
        $newUsersThisMonth = User::whereNotNull('school_id')
            ->whereMonth('created_at', now()->month)
            ->count();

        // Monthly Revenue Calculation - FIX: Specify which table's created_at
        $monthlyRevenue = Subscription::where('subscriptions.status', 'active')
                ->join('plans', 'subscriptions.plan_id', '=', 'plans.id')
                ->sum('plans.price_monthly') / 100; // Convert from cents to dollars

        // Recent Schools with user count
        $recentSchools = School::withCount('users')
            ->with(['activeSubscription.plan'])
            ->latest()
            ->take(5)
            ->get();

        // Revenue Chart Data (Last 6 months)
        $revenueData = $this->getRevenueChartData();

        // Users Growth Chart Data (Last 6 months)
        $usersData = $this->getUsersChartData();

        // Recent Activities - check if activity log table exists
        $recentActivities = collect();
        if (Schema::hasTable('activity_log')) {
            $recentActivities = \Spatie\Activitylog\Models\Activity::latest()->take(10)->get();
        }

        return view('superadmin.dashboard', compact(
            'totalSchools',
            'activeSubscriptions',
            'activeSchools',
            'totalUsers',
            'newUsersThisMonth',
            'monthlyRevenue',
            'recentSchools',
            'revenueData',
            'usersData',
            'recentActivities'
        ));
    }

    private function getRevenueChartData()
    {
        $data = [];
        $labels = [];

        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $labels[] = $month->format('M');

            // FIX: Specify subscriptions.created_at
            $revenue = Subscription::where('subscriptions.status', 'active')
                    ->whereMonth('subscriptions.created_at', $month->month)
                    ->whereYear('subscriptions.created_at', $month->year)
                    ->join('plans', 'subscriptions.plan_id', '=', 'plans.id')
                    ->sum('plans.price_monthly') / 100; // Convert from cents

            $data[] = $revenue;
        }

        return [
            'labels' => $labels,
            'data' => $data
        ];
    }

    private function getUsersChartData()
    {
        $data = [];
        $labels = [];

        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $labels[] = $month->format('M');

            $count = User::whereNotNull('school_id')
                ->whereMonth('created_at', $month->month)
                ->whereYear('created_at', $month->year)
                ->count();

            $data[] = $count;
        }

        return [
            'labels' => $labels,
            'data' => $data
        ];
    }
}
