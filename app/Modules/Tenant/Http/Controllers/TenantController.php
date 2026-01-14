<?php

namespace App\Modules\Tenant\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Tenant\Services\TenantService;
use Illuminate\Http\Request;

class TenantController extends Controller
{
    protected $tenantService;

    public function __construct(TenantService $tenantService)
    {
        $this->tenantService = $tenantService;
    }

    public function index()
    {
        $data = \App\Modules\Tenant\Models\Tenant::latest()->paginate(10);
        return view("Tenant::index", compact('data'));
    }

    public function create()
    {
        $data = new \stdClass();
        $data->title = "Create New Tenant";
        $data->route = route('dashboard.tenants.store');
        return view("Tenant::create", compact('data'));
    }

    public function store(\App\Modules\Tenant\Http\Requests\StoreTenantRequest $request)
    {
        $tenant = $this->tenantService->createTenant($request->validated());
        return redirect()->route('dashboard.tenants.index')->with('success', 'Tenant created successfully');
    }

    public function edit($id)
    {
        $tenant = \App\Modules\Tenant\Models\Tenant::findOrFail($id);
        
        $data = new \stdClass();
        $data->title = "Edit Tenant: " . $tenant->name;
        $data->route = route('dashboard.tenants.update', $tenant->id);
        $data->method = "Update"; 
        
        return view("Tenant::edit", compact('tenant', 'data'));
    }

    public function update(Request $request, $id)
    {
        $tenant = \App\Modules\Tenant\Models\Tenant::findOrFail($id);
        
        $data = $request->only('name', 'status', 'is_active', 'storage_limit', 'locale', 'timezone');
        
        // Convert Storage Limit from MB to Bytes if present
        if (isset($data['storage_limit'])) {
            $data['storage_limit'] = (int) $data['storage_limit'] * 1024 * 1024;
        }

        $tenant->update($data);
        
        return custom_response($tenant, ['message' => 'Tenant updated successfully', 'redirect' => route('dashboard.tenants.index')]);
    }

    public function branding($id)
    {
        $tenant = \App\Modules\Tenant\Models\Tenant::findOrFail($id);
        
        $data = new \stdClass();
        $data->title = "Branding: " . $tenant->name;
        $data->route = route('dashboard.tenants.branding.update', $tenant->id);
        $data->method = "Save"; // Use Save to avoid triggering the _method=PUT logic in blade
        
        return view("Tenant::branding", compact('tenant', 'data'));
    }

    public function updateBranding(\App\Modules\Tenant\Http\Requests\UpdateBrandingRequest $request, $id)
    {
         $tenant = \App\Modules\Tenant\Models\Tenant::findOrFail($id);
         $this->tenantService->updateBranding($tenant, $request->validated());
         return redirect()->route('dashboard.tenants.index')->with('success', 'Branding updated successfully');
    }
}
