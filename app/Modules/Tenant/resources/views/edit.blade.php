@extends('Main::widgets.modal.ajaxify-modal', ['data' => $data ?? ''])

@section('form')
<div class="row clearfix">
    <!-- ID Hidden Field needs to be passed for update URL in ajaxify-modal if generic logic is used, 
         or just handled by the form action passed to the view -->
    
    <div class="col-md-12 mb-2">
        {{ Form::label('name', 'Tenant Name', ['class' => 'required']) }}
        <div class="form-group">
            {{ Form::text('name', $tenant->name ?? null, ['class' => 'form-control', 'required' => true]) }}
        </div>
    </div>

    <div class="col-md-12 mb-2">
        {{ Form::label('subdomain', 'Subdomain') }}
        <div class="form-group">
            {{ Form::text('subdomain', $tenant->subdomain ?? null, ['class' => 'form-control', 'disabled' => true, 'readonly' => true]) }}
            <small class="text-muted">Subdomains cannot be changed.</small>
        </div>
    </div>

    <div class="col-md-12 mb-2">
        {{ Form::label('status', 'Status', ['class' => 'required']) }}
        <div class="form-group">
            {{ Form::select('status', ['active' => 'Active', 'trial' => 'Trial', 'suspended' => 'Suspended'], $tenant->status ?? 'active', ['class' => 'form-control select2']) }}
        </div>
    </div>

    <div class="col-md-12 mt-2">
        <div class="form-group"> 
            <div class="form-check-inline">
                <div class="fancy-checkbox">
                    <label>
                        {{ Form::checkbox('is_active', 1, $tenant->is_active ?? true) }} 
                        <span>{{ __('Is Active Access') }}</span>
                    </label>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-12 mb-2">
        {{ Form::label('storage_limit', 'Storage Limit (MB)') }}
        <div class="form-group">
            @php
                $storageMB = ($tenant->storage_limit) ? $tenant->storage_limit / 1024 / 1024 : 0;
            @endphp
            {{ Form::number('storage_limit', $storageMB, ['class' => 'form-control']) }}
        </div>
    </div>

    <div class="col-md-12 mb-2">
        {{ Form::label('locale', 'Locale') }}
        <div class="form-group">
            {{ Form::select('locale', ['en' => 'English', 'es' => 'Spanish', 'fr' => 'French'], $tenant->locale ?? 'en', ['class' => 'form-control select2']) }}
        </div>
    </div>

    <div class="col-md-12 mb-2">
        {{ Form::label('timezone', 'Timezone') }}
        <div class="form-group">
             {{ Form::select('timezone', ['UTC' => 'UTC', 'America/New_York' => 'Eastern Time', 'Asia/Dhaka' => 'Dhaka'], $tenant->timezone ?? 'UTC', ['class' => 'form-control select2']) }}
        </div>
    </div>
</div>

<script src="{{ asset('assets/js/common.js') }}"></script>
@endsection
