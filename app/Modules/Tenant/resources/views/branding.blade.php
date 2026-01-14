@extends('Main::widgets.modal.ajaxify-modal', ['data' => $data ?? ''])

@section('form')
<div class="row clearfix">
    <div class="col-md-12 mb-2">
        {{ Form::label('primary_color', 'Primary Color') }}
        <div class="form-group">
            {{ Form::color('primary_color', $tenant->primary_color ?? '#3b82f6', ['class' => 'form-control form-control-color w-100', 'title' => 'Choose your color']) }}
        </div>
    </div>

    <div class="col-md-12 mb-2">
        {{ Form::label('secondary_color', 'Secondary Color') }}
        <div class="form-group">
            {{ Form::color('secondary_color', $tenant->secondary_color ?? '#64748b', ['class' => 'form-control form-control-color w-100', 'title' => 'Choose your color']) }}
        </div>
    </div>

    <div class="col-md-12 mb-2">
        {{ Form::label('logo', 'Logo') }}
        <div class="form-group mb-2">
            {{ Form::file('logo', ['class' => 'form-control']) }}
            <small class="text-muted">Upload an image file</small>
        </div>
        <div class="form-group">
            {{ Form::url('logo_url', $tenant->logo ?? null, ['class' => 'form-control', 'placeholder' => 'Or enter URL: https://...', 'name' => 'logo']) }}
        </div>
    </div>

    <div class="col-md-12 mb-2">
        {{ Form::label('favicon', 'Favicon') }}
        <div class="form-group mb-2">
            {{ Form::file('favicon', ['class' => 'form-control']) }}
            <small class="text-muted">Upload an image file</small>
        </div>
        <div class="form-group">
             {{ Form::url('favicon_url', $tenant->favicon ?? null, ['class' => 'form-control', 'placeholder' => 'Or enter URL: https://...', 'name' => 'favicon']) }}
        </div>
    </div>
</div>

<script src="{{ asset('assets/js/common.js') }}"></script>
@endsection
