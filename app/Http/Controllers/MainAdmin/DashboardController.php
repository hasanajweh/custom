<?php

namespace App\Http\Controllers\MainAdmin;

use App\Http\Controllers\Controller;
use App\Models\Network;
use Illuminate\Contracts\View\View;

class DashboardController extends Controller
{
    public function index(Network $network): View
    {
        $branches = $network->branches()->withCount([
            'users as admins_count' => function ($query) {
                $query->where('role', 'admin');
            },
            'users as supervisors_count' => function ($query) {
                $query->where('role', 'supervisor');
            },
            'users as teachers_count' => function ($query) {
                $query->where('role', 'teacher');
            },
            'fileSubmissions',
        ])->get();

        return view('main-admin.dashboard', [
            'network' => $network,
            'branches' => $branches,
        ]);
    }
}
