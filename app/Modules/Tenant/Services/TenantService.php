<?php

namespace App\Modules\Tenant\Services;

use App\Modules\Tenant\Models\Tenant;
use Illuminate\Support\Facades\DB;
use Exception;

class TenantService
{
    /**
     * Create a new tenant.
     *
     * @param array $data
     * @return Tenant
     */
    public function createTenant(array $data): Tenant
    {
        return DB::transaction(function () use ($data) {
            // Default configuration
            $data['status'] = $data['status'] ?? 'trial';
            $data['locale'] = $data['locale'] ?? 'en';
            $data['timezone'] = $data['timezone'] ?? 'UTC';

            // Convert Storage Limit from MB to Bytes if present
            if (isset($data['storage_limit'])) {
                $data['storage_limit'] = (int) $data['storage_limit'] * 1024 * 1024;
            }

            $tenant = Tenant::create($data);

            // TODO: Trigger any necessary events (e.g., Domain provisioning, Database creation if using separate DBs)

            return $tenant;
        });
    }

    /**
     * Update tenant branding options.
     *
     * @param Tenant $tenant
     * @param array $brandingData
     * @return Tenant
     */
    public function updateBranding(Tenant $tenant, array $brandingData): Tenant
    {
        // Handle File Uploads (simple local storage for now)
        if (isset($brandingData['logo']) && $brandingData['logo'] instanceof \Illuminate\Http\UploadedFile) {
            $path = $brandingData['logo']->store('tenants/' . $tenant->id . '/branding', 'public');
            $brandingData['logo'] = asset('storage/' . $path);
        }
        
        if (isset($brandingData['favicon']) && $brandingData['favicon'] instanceof \Illuminate\Http\UploadedFile) {
            $path = $brandingData['favicon']->store('tenants/' . $tenant->id . '/branding', 'public');
            $brandingData['favicon'] = asset('storage/' . $path);
        }

        $tenant->update([
            'logo' => $brandingData['logo'] ?? $tenant->logo,
            'favicon' => $brandingData['favicon'] ?? $tenant->favicon,
            'primary_color' => $brandingData['primary_color'] ?? $tenant->primary_color,
            'secondary_color' => $brandingData['secondary_color'] ?? $tenant->secondary_color,
            'branding_config' => array_merge($tenant->branding_config ?? [], $brandingData['config'] ?? []),
        ]);

        return $tenant;
    }

    /**
     * Suspend a tenant.
     *
     * @param Tenant $tenant
     * @return bool
     */
    public function suspendTenant(Tenant $tenant): bool
    {
        return $tenant->update(['status' => 'suspended', 'is_active' => false]);
    }

    /**
     * Activate a tenant.
     *
     * @param Tenant $tenant
     * @return bool
     */
    public function activateTenant(Tenant $tenant): bool
    {
        return $tenant->update(['status' => 'active', 'is_active' => true]);
    }

    /**
     * Get tenant by subdomain.
     *
     * @param string $subdomain
     * @return Tenant|null
     */
    public function getBySubdomain(string $subdomain): ?Tenant
    {
        return Tenant::where('subdomain', $subdomain)->first();
    }
}
