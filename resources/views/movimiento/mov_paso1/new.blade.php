@extends('admin.layout')

@section('title', '- Movimientos, nuevo movimiento')

@push('after_all_scripts')
    <script src="{{ asset('admin/js/movimientosPaso1.js?v=20200226') }}"></script>
@endpush

@section('breadcrumb')
	<li class="breadcrumb-item">
		<a href="{{route('movimientos.index')}}"> Movimientos</a>
	</li>
	<li class="breadcrumb-item">
		Paso 1 - Datos generales
	</li>
@endsection

@section('content')

		<div class="card card-accent-info">
		  <h5 class="card-header">Paso 1 - Datos generales </h5>
		  <div class="card-body">
			{!! Form::open(['route' => 'movimientos.store', 'files' => true]) !!}
			@include('movimiento.mov_paso1._form')

			<div class="text-center mb-3">
				<a href="{{route('movimientos.index')}}" class="btn btn-secondary">Cancelar</a>
				{!! Form::submit('Crear movimiento', ['class'=>'btn btn-primary']) !!}
			</div>
			{!! Form::close() !!}
		  </div>
		</div>

@endsection
