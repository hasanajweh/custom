<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Network;
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
            ->with(['activeSubscription.plan', 'network']);

        if ($request->filled('search')) {
            $searchTerm = '%' . $request->search . '%';
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', $searchTerm)
                    ->orWhere('slug', 'like', $searchTerm);
            });
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        if ($request->filled('network')) {
            $query->where('network_id', $request->network);
        }

        $schools = $query->latest()->paginate(12);
        $networks = Network::orderBy('name')->get();

        return view('superadmin.schools.index', compact('schools', 'networks'));
    }

    // STEP 1: School Info + Plan Selection
    public function create()
    {
        $plan = Plan::where('slug', 'branches-plan')
            ->orWhere('name', 'Branches Plan')
            ->first();

        if (! $plan) {
            return redirect()->route('superadmin.plans.create')
                ->with('error', 'Please create the Branches Plan before adding schools.');
        }

        $networks = Network::orderBy('name')->get();

        return view('superadmin.schools.create', compact('plan', 'networks'));
    }

    public function store(Request $request)
    {
        // Log the incoming request
        \Log::info('School creation attempt', $request->all());

        $validated = $request->validate([
            'network_mode' => ['required', Rule::in(['existing', 'new'])],
            'network_id' => ['required_if:network_mode,existing', 'exists:networks,id'],
            'network_name' => ['required_if:network_mode,new', 'string', 'max:255'],
            'network_slug' => ['required_if:network_mode,new', 'string', 'max:255', 'alpha_dash', 'unique:networks,slug'],
            'main_admin_name' => ['required_if:network_mode,new', 'string', 'max:255'],
            'main_admin_email' => ['required_if:network_mode,new', 'string', 'email', 'max:255', 'unique:users,email'],
            'main_admin_password' => ['required_if:network_mode,new', 'confirmed', 'min:8'],
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'alpha_dash', 'unique:schools,slug'],
            'admin_name' => ['required', 'string', 'max:255'],
            'admin_email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'admin_password' => ['required', 'confirmed', 'min:8'],
        ]);

        DB::beginTransaction();

        try {
            $plan = Plan::where('slug', 'branches-plan')
                ->orWhere('name', 'Branches Plan')
                ->firstOrFail();

            if ($request->network_mode === 'existing') {
                $network = Network::findOrFail($request->network_id);
            } else {
                $network = Network::create([
                    'name' => $request->network_name,
                    'slug' => Str::slug($request->network_slug),
                    'plan_name' => 'branches',
                    'is_active' => true,
                ]);

                $mainAdmin = $network->mainAdmin;

                if (! $mainAdmin) {
                    $mainAdmin = $network->mainAdmin()->create([
                        'name' => $request->main_admin_name,
                        'email' => $request->main_admin_email,
                        'password' => Hash::make($request->main_admin_password),
                        'role' => 'main_admin',
                        'is_main_admin' => true,
                        'is_active' => true,
                    ]);
                }
            }

            // 1. Create school (INACTIVE until subscription is activated)
            $school = School::create([
                'name' => $request->name,
                'slug' => Str::slug($request->slug),
                'network_id' => $network->id,
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
                'network_id' => $network->id,
                'email_verified_at' => now(),
            ]);

            \Log::info('Admin user created', ['user_id' => $user->id]);

            // 3. Create PENDING subscription
            $subscription = $school->subscriptions()->create([
                'plan_id' => $plan->id,
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
        $plans = Plan::where('is_active', true)
            ->where(function ($query) {
                $query->where('slug', 'branches-plan')
                    ->orWhere('name', 'Branches Plan');
            })
            ->get();
        $currentSubscription = $school->activeSubscription ?? $school->subscriptions()->latest()->first();
        $networks = Network::orderBy('name')->get();

        return view('superadmin.schools.edit', compact('school', 'plans', 'currentSubscription', 'networks'));
    }

    public function update(Request $request, School $school)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'alpha_dash', Rule::unique('schools')->ignore($school->id)],
            'storage_limit_gb' => ['required', 'integer', 'min:1', 'max:1000'],
            'network_id' => ['required', 'exists:networks,id'],
            'plan_id' => ['nullable', 'exists:plans,id'], // Optional plan change
        ]);

        DB::beginTransaction();

        try {
            // Update school
            $school->update([
                'name' => $request->name,
                'slug' => Str::slug($request->slug),
                'is_active' => $request->has('is_active'),
                'network_id' => $request->network_id,
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
