<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Modules\Tenant\Models\Tenant;

class TenantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Alpha Corp (Active)
        Tenant::updateOrCreate(
            ['subdomain' => 'alpha'],
            [
                'name' => 'Alpha Corp',
                'custom_domain' => null,
                'status' => 'active',
                'is_active' => true,
                'locale' => 'en',
                'timezone' => 'UTC',
                'primary_color' => '#3b82f6',
                'feature_flags' => json_encode(['beta_features' => true]),
                'storage_limit' => 5 * 1024 * 1024 * 1024, // 5GB
                'user_limit' => 20,
            ]
        );

        // 2. Beta Startups (Trial)
        Tenant::updateOrCreate(
            ['subdomain' => 'beta'],
            [
                'name' => 'Beta Startups',
                'custom_domain' => 'beta-startups.com',
                'status' => 'trial',
                'is_active' => true,
                'trial_ends_at' => now()->addDays(14),
                'primary_color' => '#10b981',
                'storage_limit' => 1 * 1024 * 1024 * 1024, // 1GB
                'user_limit' => 5,
            ]
        );

        // 3. Omega Legacy (Suspended)
        Tenant::updateOrCreate(
            ['subdomain' => 'omega'],
            [
                'name' => 'Omega Legacy',
                'status' => 'suspended',
                'is_active' => false,
                'primary_color' => '#ef4444',
            ]
        );
    }
}
