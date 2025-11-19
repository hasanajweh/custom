<?php

namespace App\Http\Controllers\MainAdmin;

use App\Http\Controllers\Controller;
use App\Models\Network;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class HierarchyController extends Controller
{
    public function index(Network $network): View
    {
        $branches = $network->branches()->withCount(['subjects', 'grades', 'fileSubmissions'])->get()->map(function ($branch) {
            $branch->admins_count = DB::table('branch_user_roles')->where('school_id', $branch->id)->where('role', 'admin')->count();
            $branch->supervisors_count = DB::table('branch_user_roles')->where('school_id', $branch->id)->where('role', 'supervisor')->count();
            $branch->teachers_count = DB::table('branch_user_roles')->where('school_id', $branch->id)->where('role', 'teacher')->count();
            return $branch;
        });

        return view('main-admin.hierarchy', [
            'network' => $network,
            'branches' => $branches,
        ]);
    }
}
