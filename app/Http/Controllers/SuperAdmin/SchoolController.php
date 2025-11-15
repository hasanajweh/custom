<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use App\Models\School;
use App\Models\User;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;
use Carbon\Carbon;

class SchoolController extends Controller
{
    public function index(Request $request)
    {
        $query = School::withoutGlobalScopes()
            ->withCount(['users', 'fileSubmissions'])
            ->with('activeSubscription.plan');

        if ($request->filled('search')) {
            $searchTerm = '%' . $request->search . '%';
            $query->where('name', 'like', $searchTerm)
                ->orWhere('slug', 'like', $searchTerm);
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $schools = $query->latest()->paginate(12);

        return view('superadmin.schools.index', compact('schools'));
    }

    // STEP 1: School Info + Plan Selection
    public function create()
    {
        $plans = Plan::where('is_active', true)->get();

        if ($plans->isEmpty()) {
            return redirect()->route('superadmin.plans.create')
                ->with('error', 'Please create at least one active plan first.');
        }

        return view('superadmin.schools.create', compact('plans'));
    }

    public function store(Request $request)
    {
        // Log the incoming request
        \Log::info('School creation attempt', $request->all());

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'alpha_dash', 'unique:schools,slug'],
            'plan_id' => ['required', 'exists:plans,id'],
            'admin_name' => ['required', 'string', 'max:255'],
            'admin_email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'admin_password' => ['required', 'confirmed', 'min:8'],
        ]);

        DB::beginTransaction();

        try {
            $plan = Plan::findOrFail($request->plan_id);

            // 1. Create school (INACTIVE until subscription is activated)
            $school = School::create([
                'name' => $request->name,
                'slug' => Str::slug($request->slug),
                'is_active' => false,
                'storage_limit' => $plan->storage_limit_in_gb * 1073741824,
                'storage_used' => 0,
            ]);

            \Log::info('School created', ['school_id' => $school->id]);

            // 2. Create admin user for the school
            $user = $school->users()->create([
                'name' => $request->admin_name,
                'email' => $request->admin_email,
                'password' => Hash::make($request->admin_password),
                'role' => 'admin',
                'email_verified_at' => now(),
            ]);

            \Log::info('Admin user created', ['user_id' => $user->id]);

            // 3. Create PENDING subscription
            $subscription = $school->subscriptions()->create([
                'plan_id' => $request->plan_id,
                'status' => 'pending',
                'starts_at' => now(),
                'ends_at' => now()->addYear(),
            ]);

            \Log::info('Subscription created', ['subscription_id' => $subscription->id]);

            DB::commit();

            return redirect()->route('superadmin.subscriptions.index')
                ->with('success', "School '{$school->name}' created successfully! Subscription is pending activation.");

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('School creation failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return back()
                ->withInput()
                ->withErrors(['error' => 'Failed to create school: ' . $e->getMessage()]);
        }
    }

    // STEP 2: Create Admin User
    public function createAdmin(School $school)
    {
        $plan = $school->subscriptions()->latest()->first()?->plan;
        return view('superadmin.schools.create-admin', compact('school', 'plan'));
    }



    // EDIT School (with plan change + storage slider)
    public function edit(School $school)
    {
        $plans = Plan::where('is_active', true)->get();
        $currentSubscription = $school->activeSubscription ?? $school->subscriptions()->latest()->first();

        return view('superadmin.schools.edit', compact('school', 'plans', 'currentSubscription'));
    }

    public function update(Request $request, School $school)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'alpha_dash', Rule::unique('schools')->ignore($school->id)],
            'storage_limit_gb' => ['required', 'integer', 'min:1', 'max:1000'],
            'plan_id' => ['nullable', 'exists:plans,id'], // Optional plan change
        ]);

        DB::beginTransaction();

        try {
            // Update school
            $school->update([
                'name' => $request->name,
                'slug' => Str::slug($request->slug),
                'is_active' => $request->has('is_active'),
                'storage_limit' => $request->storage_limit_gb * 1073741824,
            ]);

            // If plan changed, update the subscription
            if ($request->filled('plan_id')) {
                $subscription = $school->subscriptions()->latest()->first();
                if ($subscription && $subscription->plan_id != $request->plan_id) {
                    $subscription->update([
                        'plan_id' => $request->plan_id,
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('superadmin.schools.index')
                ->with('success', 'School updated successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error updating school: ' . $e->getMessage());
        }
    }

    public function destroy(School $school)
    {
        DB::beginTransaction();

        try {
            $school->fileSubmissions()->delete();
            $school->subscriptions()->delete();
            $school->users()->delete();
            $school->delete();

            DB::commit();

            return redirect()->route('superadmin.schools.index')
                ->with('success', 'School deleted successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error deleting school: ' . $e->getMessage());
        }
    }
}
