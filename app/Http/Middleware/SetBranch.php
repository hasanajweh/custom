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
        try {
            $network = $request->attributes->get('network') ?? $request->route('network');
            if (is_string($network)) {
                $network = Network::where('slug', $network)->first();
            }

            $branchParam = $request->route('branch') ?? $request->route('school');
            
            if (! $branchParam) {
                \Log::warning('SetBranch middleware: No branch parameter in route', [
                    'route' => $request->route()?->getName(),
                    'url' => $request->url(),
                ]);
                abort(404, 'Branch parameter is required');
            }

            $branch = $branchParam instanceof School
                ? $branchParam
                : School::where('slug', $branchParam)->first();

            if (! $branch) {
                \Log::warning('SetBranch middleware: Branch not found', [
                    'slug' => $branchParam,
                    'route' => $request->route()?->getName(),
                ]);
                abort(404, 'Branch not found');
            }

            if ($network instanceof Network && $branch->network_id !== $network->id) {
                \Log::warning('SetBranch middleware: Branch network mismatch', [
                    'branch_id' => $branch->id,
                    'branch_network_id' => $branch->network_id,
                    'route_network_id' => $network->id,
                ]);
                abort(404, 'Branch does not belong to this network');
            }
        } catch (\Exception $e) {
            \Log::error('SetBranch middleware exception', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            abort(500, 'Error setting branch context');
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
