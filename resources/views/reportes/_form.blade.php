@push('after_all_styles')
    <link href="{{asset('admin/vendors/bootstrap-duallistbox/src/bootstrap-duallistbox.css')}}" rel="stylesheet">
    <link href="{{asset('admin/vendors/lightbox-master/dist/ekko-lightbox.css')}}" rel="stylesheet">
    <link href="{{ asset('admin/vendors/bs-stepper/bs-stepper.min.css') }}" rel="stylesheet" />
@endpush

@push('after_all_scripts')
    <script src="{{ asset('admin/vendors/bootstrap-duallistbox/dist/jquery.bootstrap-duallistbox.min.js') }}"></script>
    <script src="{{ asset('admin/vendors/sortable/Sortable.min.js') }}"></script>
    <script src="{{ asset('admin/vendors/bs-stepper/bs-stepper.min.js') }}"></script>
    <script src="{{asset('admin/vendors/lightbox-master/dist/ekko-lightbox.min.js')}}"></script>

    <script src="{{ asset('admin/js/reportesCreateEdit.js?v=20200420') }}"></script>
@endpush

@push('script_head')
    <script src="{{asset('vendor/datatables/buttons.server-side.js?v=20200227')}}"></script>
@endpush


@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
    <div id="stepper1" class="bs-stepper">
        <div class="bs-stepper-header" role="tablist">
            <div class="step" data-target="#tab-1">
                <button type="button" class="step-trigger" role="tab" id="stepper1trigger1" aria-controls="test-l-1">
                    <span class="bs-stepper-circle">1</span>
                    <span class="bs-stepper-label">Datos del reporte</span>
                </button>
            </div>
            <div class="bs-stepper-line"></div>
            <div class="step" data-target="#tab-2">
                <button type="button" class="step-trigger" role="tab" id="stepper1trigger2" aria-controls="test-l-2">
                    <span class="bs-stepper-circle">2</span>
                    <span class="bs-stepper-label">Datos para exportar</span>
                </button>
            </div>
            <div class="bs-stepper-line"></div>
            <div class="step" data-target="#tab-3">
                <button type="button" class="step-trigger" role="tab" id="stepper1trigger3" aria-controls="test-l-2">
                    <span class="bs-stepper-circle">3</span>
                    <span class="bs-stepper-label">Seleccionar piezas</span>
                </button>
            </div>
        </div>
        <div class="bs-stepper-content">
            @include('reportes._paso1')
            @include('reportes._paso2')
            @include('reportes._paso3')
        </div>
    </div>
{!! $dataTable->scripts() !!}
