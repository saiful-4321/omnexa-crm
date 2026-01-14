<div class="modal-dialog {{ $data->modal_size ?? '' }}">
    <div class="modal-content">
        <div class="modal-header">
            <h6 class="title font-weight-bold mb-0" id="defaultModalLabel">{{ $data->method ?? '' }} {{ $data->page ?? '' }}</h6>
        </div>
        {!! Form::model($data->item ?? '', ['url' => $data->action ?? '', 'class' => 'dynamicModal', 'files'=> true]) !!}
        @if (!empty($data->method) && $data->method == 'Update')
            {{ Form::hidden('_method', 'PUT') }}
        @endif
        <div class="modal-body">
            @yield('form')
        </div>
        <div class="d-flex align-items-center justify-content-between modal-footer border-0 pt-2 " style="display: flex !important; gap: 10px;">
            @if(isset($data->modal_only) && $data->modal_only == true)
                <button type="button" class="btn btn-outline-danger w-100" data-bs-dismiss="modal" data-bs-dismiss="offcanvas">{{ ucfirst(($data->button ?? 'Close')) }}</button>
            @else
                <button type="button" class="btn btn-light w-50" data-bs-dismiss="modal" data-bs-dismiss="offcanvas">Cancel</button>
                <button type="submit" class="btn btn-primary w-50">{{ ucfirst(($data->button ?? ($data->method ?? 'Submit'))) }}</button>
            @endif
        </div>
        {!! Form::close() !!}
    </div>
</div>



