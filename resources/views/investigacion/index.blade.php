@extends('admin.layout')

@section('title', '- Investigación')

@section('breadcrumb')
    <li class="breadcrumb-item">
        Investigación
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
    {!! $dataTable->table([], true) !!}
    {!! $dataTable->scripts() !!}
@include('flash-toastr::message')
@endsection
