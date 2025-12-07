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
    public function impersonate(Request $request, Network $network)
    {
        $user = Auth::user();
        
        // Simple check: must be main admin of this network
        if (!$user->isMainAdmin() || $user->network_id !== $network->id) {
            abort(403);
        }
        
        $schoolSlug = $request->query('school');
        
        if (!$schoolSlug) {
            return redirect()->route('main-admin.hierarchy', ['network' => $network->slug])
                ->with('error', 'School parameter is required.');
        }
        
        $school = School::with('network')
            ->where('slug', $schoolSlug)
            ->where('network_id', $network->id)
            ->first();
        
        if (!$school) {
            return redirect()->route('main-admin.hierarchy', ['network' => $network->slug])
                ->with('error', 'School not found.');
        }
        
        // Set context silently (no logging)
        ActiveContext::setSchool($school->id);
        ActiveContext::setRole('admin');
        
        // Save session
        session()->save();
        
        // Redirect to school admin dashboard
        return redirect()->to(tenant_route('school.admin.dashboard', $school));
    }
}
