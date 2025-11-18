<?php

namespace App\Http\Middleware;

use App\Models\Branch;
use App\Models\Network;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Symfony\Component\HttpFoundation\Response;

class SetBranch
{
    public function handle(Request $request, Closure $next): Response
    {
        $network = $request->attributes->get('network') ?? $request->route('network');
        if (is_string($network)) {
            $network = Network::where('slug', $network)->first();
        }

        $branchParam = $request->route('branch') ?? $request->route('school');
        if (! $branchParam) {
            return $next($request);
        }

        $branch = $branchParam instanceof Branch
            ? $branchParam
            : Branch::where('slug', $branchParam)->first();

        if (! $branch || ($network instanceof Network && $branch->network_id !== $network->id)) {
            abort(404, 'Branch not found');
        }

        $request->attributes->set('branch', $branch);
        URL::defaults([
            'network' => $network?->slug,
            'branch' => $branch->slug,
            'school' => $branch->slug,
        ]);

        return $next($request);
    }
}
