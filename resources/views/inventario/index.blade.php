@extends('admin.layout')

@section('title', '- Inventario')

@section('breadcrumb')
    <li class="breadcrumb-item">
        Inventario
    </li>
@endsection

@push('after_all_styles')
    <link href="{{asset('admin/vendors/lightbox-master/dist/ekko-lightbox.css')}}" rel="stylesheet">
@endpush

@push('script_head')
    <script src="{{asset('vendor/datatables/buttons.server-side.js')}}"></script>
@endpush

@push('after_all_scripts')
    <script src="{{asset('admin/vendors/lightbox-master/dist/ekko-lightbox.min.js')}}"></script>
    <script type="text/javascript">
    $(function() {
        $(document).on('click', '[data-toggle="lightbox"]', function(event) {
            event.preventDefault();
            $(this).ekkoLightbox({
                alwaysShowClose: true,
                showArrows: true,
                onContentLoaded: function(ev) {
        			setTimeout(function() {
                        var toAppend = $('<a href="'+ $('.ekko-lightbox').find('.img-fluid').prop('src') +'" class="btn btn-success mr-2" download><i class="fas fa-download"></i></a>');
        				$('.ekko-lightbox').find('.modal-footer').prepend(toAppend);
        			}, 1000);
        		}
            });
        });
    });
    </script>
@endpush

@section('content')
    @can('agregar_inventario')
    <div class="page-action text-right mb-3">
        <a href="{{ route('inventario.create') }}" class="btn btn-outline-primary btn-sm pull-right" rel="tooltip" title="Nueva pieza"><i class="fas fa-plus"></i> Agregar</a>
    </div>
    @endcan

    {!! $dataTable->table([], true) !!}
    {!! $dataTable->scripts() !!}
@include('flash-toastr::message')
@endsection
