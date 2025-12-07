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
        
        // Set context to view as admin - this allows main admin to access school routes
        ActiveContext::setContext($school->id, 'admin');
        
        // Redirect to the actual school admin dashboard (full admin experience)
        return redirect()->to(tenant_route('school.admin.dashboard', $school));
    }
}
