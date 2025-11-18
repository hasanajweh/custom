<?php

namespace App\Http\Controllers\MainAdmin;

use App\Http\Controllers\Controller;
use App\Models\Network;
use Illuminate\View\View;

class MainAdminHierarchyController extends Controller
{
    public function index(Network $network): View
    {
        $userNetwork = auth()->user()?->network;

        abort_unless($userNetwork && $userNetwork->is($network), 404);

        $branches = $userNetwork->schools()->withCount([
            'users as admins_count' => fn ($q) => $q->where('role', 'admin'),
            'users as supervisors_count' => fn ($q) => $q->where('role', 'supervisor'),
            'users as teachers_count' => fn ($q) => $q->where('role', 'teacher'),
            'subjects',
            'grades',
        ])->get();

        return view('main-admin.hierarchy', [
            'network' => $userNetwork,
            'branches' => $branches,
        ]);
    }
}
