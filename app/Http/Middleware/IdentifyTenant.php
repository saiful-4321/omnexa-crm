<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Modules\Tenant\Services\TenantService;
use Illuminate\Support\Facades\Context; // Laravel 11+ feature, fallback to config/singleton if older

class IdentifyTenant
{
    protected $tenantService;

    public function __construct(TenantService $tenantService)
    {
        $this->tenantService = $tenantService;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $host = $request->getHost();
        $tenant = null;

        // 1. Try Custom Domain (exact match)
        // $tenant = $this->tenantService->getByCustomDomain($host);

        // 2. Try Subdomain
        // Assumes typical setup like tenant.app.com
        if (!$tenant) {
            $parts = explode('.', $host);
            // Just a basic example: if 3 parts (sub.domain.com), take first. 
            // In production, robust parsing vs CENTRAL_DOMAIN env var needed.
            if (count($parts) > 2) { 
                $subdomain = $parts[0];
                // Exclude 'www' or main app keywords
                if ($subdomain !== 'www' && $subdomain !== 'app') {
                    $tenant = $this->tenantService->getBySubdomain($subdomain);
                }
            }
        }

        if ($tenant) {
            // Check Status
            if (!$tenant->is_active || $tenant->status === 'suspended') {
                 abort(403, 'Tenant account is suspended or inactive.');
            }

            // BIND TENANT TO APP
            // Option A: Single database, scope bindings
            app()->instance('currentTenant', $tenant);
            
            // Option B: Multi-db switching would happen here
            
            // Share with views
            view()->share('currentTenant', $tenant);
        } else {
            // If strictly tenant-only routes, abort.
            // But this middleware might be global, so we might just pass through 
            // if we are on the central domain (landing page).
            
            // For now, if route requires tenant, it should check app('currentTenant')
        }

        return $next($request);
    }
}
