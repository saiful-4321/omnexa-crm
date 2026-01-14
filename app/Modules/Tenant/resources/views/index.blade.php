@extends("Main::layouts.app")

@section("content")
<div class="block-header">
    <div class="row">
        <div class="col-lg-5 col-md-8 col-sm-12">
            <h4 class="m-0">Tenants - <small>({{ request()->get("status") ? request()->get("status") : "All"  }})</small> </h2>
        </div>
        <div class="col-lg-7 col-md-4 col-sm-12 text-right">
            <ul class="breadcrumb justify-content-end m-0">
                <li class="breadcrumb-item"><a href="{{ route('dashboard.home') }}"><i class="fas fa-home"></i></a></li>
                <li class="breadcrumb-item active">Tenant Management</li>
            </ul>
        </div>
    </div>
</div>

@include("Main::widgets.message.sweet-alert")

<div class="row clearfix">
    <div class="col-lg-12 col-md-12">
        <div class="card bg-white">
            <div class="card-header border-bottom">
                <div class="d-flex align-items-center justify-content-between py-1">
                    <h6 class="font-weight-medium mb-0">Tenants List</h6>
                    
                    <div class="d-flex align-items-center gap-2">
                        @can('tenant-create')
                        <a href="javascript:void(0)" route="{{ route('dashboard.tenants.create') }}" data-toggle="commonOffcanvas" class="btn btn-info btn-sm d-flex align-items-center font-weight-medium">
                            <i class="mdi mdi-plus me-1"></i> Add New
                        </a>
                        @endcan
                    </div>
                </div>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive rounded-10 border">
                    <table class="table table-hover table-striped mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Subdomain</th>
                                <th>Status</th>
                                <th>Created At</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($data as $tenant)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($tenant->logo)
                                                <img src="{{ $tenant->logo }}" class="avatar-xs rounded-circle me-2" alt="">
                                            @else
                                                <div class="avatar-xs me-2">
                                                    <span class="avatar-title rounded-circle bg-soft-primary text-primary">
                                                        {{ substr($tenant->name, 0, 1) }}
                                                    </span>
                                                </div>
                                            @endif
                                            <div>
                                                <h5 class="font-size-14 mb-1">{{ $tenant->name }}</h5>
                                                <p class="text-muted mb-0 font-size-12">{{ $tenant->email }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-soft-info text-info">{{ $tenant->subdomain }}</span>
                                        @if($tenant->custom_domain)
                                            <br><small class="text-muted">{{ $tenant->custom_domain }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        @if($tenant->status == 'active')
                                            <span class="badge bg-soft-success text-success">Active</span>
                                        @elseif($tenant->status == 'suspended')
                                            <span class="badge bg-soft-danger text-danger">Suspended</span>
                                        @else
                                            <span class="badge bg-soft-warning text-warning">{{ ucfirst($tenant->status) }}</span>
                                        @endif
                                    </td>
                                    <td>{{ $tenant->created_at->format('d M, Y') }}</td>
                                    <td class="text-end">
                                        <!-- <div class="d-flex align-items-center gap-1"> -->
                                            @can('tenant-update')
                                            <a href="javascript:void(0)" route="{{ route('dashboard.tenants.edit', $tenant->id) }}" data-toggle="commonOffcanvas" class="btn btn-sm btn-soft-primary"><i class="mdi mdi-pencil me-1"></i></a>
                                            @endcan
                                            @can('tenant-branding-update')
                                            <a href="javascript:void(0)" route="{{ route('dashboard.tenants.branding', $tenant->id) }}" data-toggle="commonOffcanvas" class="btn btn-sm btn-soft-info"><i class="mdi mdi-palette me-1"></i></a>
                                            @endcan
                                        <!-- </div> -->
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center">No tenants found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            
            @if ($data->hasPages())
                <div class="d-flex justify-content-end mt-3 mb-0">
                    {!! $data->links() !!}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
