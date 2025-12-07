<?php

namespace App\Http\Controllers\MainAdmin;

use App\Http\Controllers\Controller;
use App\Models\Network;
use App\Models\School;
use App\Services\ActiveContext;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SchoolViewController extends Controller
{
    public function view(Network $network, string $schoolSlug)
    {
        $user = Auth::user();
        
        if (!$user->isMainAdmin() || $user->network_id !== $network->id) {
            abort(403);
        }
        
        $school = School::where('slug', $schoolSlug)
            ->where('network_id', $network->id)
            ->firstOrFail();
        
        // Set context to view as admin
        ActiveContext::setContext($school->id, 'admin');
        
        // Redirect to school admin dashboard
        return redirect()->route('main-admin.school.dashboard', [
            'network' => $network->slug,
            'schoolSlug' => $school->slug
        ]);
    }
    
    public function dashboard(Network $network, string $schoolSlug)
    {
        $user = Auth::user();
        
        if (!$user->isMainAdmin() || $user->network_id !== $network->id) {
            abort(403);
        }
        
        $school = School::where('slug', $schoolSlug)
            ->where('network_id', $network->id)
            ->firstOrFail();
        
        // Load school admin dashboard data
        $school->loadCount([
            'users',
            'subjects',
            'grades',
            'fileSubmissions',
            'fileSubmissions as recent_files_count' => function ($query) {
                $query->where('created_at', '>=', now()->subHours(72));
            },
        ]);
        
        // Get role counts
        $roleCounts = $school->schoolUserRoles()
            ->selectRaw('role, COUNT(DISTINCT user_id) as total')
            ->groupBy('role')
            ->pluck('total', 'role');
        
        return view('main-admin.school.dashboard', [
            'network' => $network,
            'school' => $school,
            'roleCounts' => $roleCounts,
        ]);
    }
}
