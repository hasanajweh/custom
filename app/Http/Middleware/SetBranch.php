<?php

namespace App\Http\Middleware;

use App\Models\Network;
use App\Models\School;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App as Application;
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
        $branch = $branchParam instanceof School
            ? $branchParam
            : School::where('slug', $branchParam)->first();

        if (! $branch || ($network instanceof Network && $branch->network_id !== $network->id)) {
            abort(404, 'Branch not found');
        }

        $request->attributes->set('branch', $branch);
        $request->attributes->set('school', $branch);
        Application::instance(School::class, $branch);
        view()->share('branch', $branch);
        view()->share('school', $branch);
        if ($network instanceof Network) {
            Application::instance(Network::class, $network);
            view()->share('network', $network);
        }
        URL::defaults([
            'network' => $network?->slug,
            'branch' => $branch->slug,
            'school' => $branch->slug,
        ]);

        return $next($request);
    }
}
