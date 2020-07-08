@extends('admin.layout')

@section('title', '- Movimientos, Paso 3 - Información del movimiento')

@section('breadcrumb')
	<li class="breadcrumb-item">
		<a href="{{route('movimientos.index')}}">Movimientos</a>
	</li>
	<li class="breadcrumb-item active">
		Paso 3 - Información del movimiento
	</li>
@endsection

@section('content')
	@include('movimiento.mov_paso3._form')

	{!! Form::model($movimiento, ['method' => 'PUT', 'route' => ['movimientos.update', $movimiento->id], 'id' => 'frmAutorizarMov']) !!}
	@if(($movimiento->authorized_by_collections == null) && ($movimiento->authorized_by_exhibitions == null))
		@canany(['autorizar_colecciones', 'autorizar_exposiciones'])
			<input type="hidden" name="p3" value="3">
			<div class="d-flex justify-content-center">
				<button class="btn btn-lg btn-danger" type="submit">Autorizar movimiento <i class="far fa-thumbs-up"></i></button>
			</div>
		@else
			<div class="alert alert-danger text-center lead">
				<strong>Solicite al responsable de colecciones que autorice el movimiento</strong>
			</div>
		@endcanany
	@elseif (((integer)$movimiento->authorized_by_collections > 0) || ((integer)$movimiento->authorized_by_exhibitions > 0))
		<div class="alert alert-warning text-center lead">
			<strong>El movimiento ha sido autorizado</strong>
		</div>
	@endif
	{!! Form::close() !!}

	@include('flash-toastr::message')
@endsection
