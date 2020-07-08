@extends('admin.layout')

@section('title', '- Movimientos, Paso 2 - Selección de piezas')

@section('assets')
<script src="{{ asset('admin/js/SelectPzaMov.js?v=20200421') }}"></script>
@endsection

@section('breadcrumb')
	<li class="breadcrumb-item">
		<a href="{{route('movimientos.index')}}">Movimientos</a>
	</li>
	<li class="breadcrumb-item">
		<a href="{{route('movimientos.edit', ['id' => $id])}}">Paso 1 - Datos generales</a>
	</li>
	<li class="breadcrumb-item active">
		Paso 2 - Selección de piezas
	</li>
@endsection

@section('content')
<div>
{!! Form::model($id, ['method' => 'PUT', 'route' => ['movimientos.update',  $id], 'id' => 'frmMovimientop2']) !!}
	@include('movimiento.mov_paso2._form')

{!! Form::close() !!}
</div>
@include('flash-toastr::message')
@endsection

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
