@extends('admin.layout')

@section('title', '- Movimiento, paso 1')

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
		  <h5 class="card-header">Editando, Paso 1 - Datos generales </h5>
		  <div class="card-body">
			{!!Form::model($movimiento, ['method' => 'PUT', 'route' => ['movimientos.update',  $movimiento->id ], 'files' => true, 'id' => 'frmEditmovimientoP1']) !!}
			@include('movimiento.mov_paso1._form')
			 <div class="text-center mb-3">
				<a href="{{route('movimientos.index')}}" class="btn btn-secondary">Cancelar</a>
				{!! Form::submit('Editar movimiento', ['class'=>'btn btn-primary']) !!}
			 </div>
			{!! Form::close() !!}
		   </div>
		</div>

@endsection
