@extends('Main::widgets.modal.ajaxify-modal', ['data' => $data ?? ''])

@section('form')
<div class="row clearfix">
    <div class="col-md-12 mb-2">
        {{ Form::label('name', 'Tenant Name', ['class' => 'required']) }}
        <div class="form-group">
            {{ Form::text('name', null, ['class' => 'form-control', 'required' => true, 'placeholder' => 'e.g. Acme Corp']) }}
        </div>
    </div>

    <div class="col-md-12 mb-2">
        {{ Form::label('subdomain', 'Subdomain', ['class' => 'required']) }}
        <div class="input-group">
            {{ Form::text('subdomain', null, ['class' => 'form-control', 'required' => true, 'placeholder' => 'acme']) }}
            <span class="input-group-text">.omnexa.com</span>
        </div>
    </div>

    <div class="col-md-12 mb-2">
        {{ Form::label('custom_domain', 'Custom Domain') }}
        <div class="form-group">
            {{ Form::text('custom_domain', null, ['class' => 'form-control', 'placeholder' => 'e.g. crm.acme.com']) }}
        </div>
    </div>

    <div class="col-md-12 mb-2">
        {{ Form::label('email', 'Admin Email', ['class' => 'required']) }}
        <div class="form-group">
            {{ Form::email('email', null, ['class' => 'form-control', 'required' => true, 'placeholder' => 'admin@acme.com']) }}
        </div>
    </div>

    <div class="col-md-12 mb-2">
        {{ Form::label('storage_limit', 'Storage Limit (MB)') }}
        <div class="form-group">
            {{ Form::number('storage_limit', 5120, ['class' => 'form-control', 'placeholder' => '5120']) }}
        </div>
    </div>
</div>

<script src="{{ asset('assets/js/common.js') }}"></script>
@endsection
