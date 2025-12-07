<?php

namespace App\Http\Controllers\MainAdmin;

use App\Http\Controllers\Controller;
use App\Models\Network;
use Illuminate\View\View;

class HierarchyController extends Controller
{
    public function index(Network $network): View
    {
        $branches = $network->branches()->withCount([
            'schoolUserRoles as admins_count' => fn($q) => $q->where('role', 'admin'),
            'schoolUserRoles as supervisors_count' => fn($q) => $q->where('role', 'supervisor'),
            'schoolUserRoles as teachers_count' => fn($q) => $q->where('role', 'teacher'),
            'subjects',
            'grades',
        ])->get();

        return view('main-admin.hierarchy', [
            'network' => $network,
            'branches' => $branches,
        ]);
    }
}
