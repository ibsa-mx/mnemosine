@extends('admin.layout')
@section('title', '-Sede, Nueva sede')
@section('assets')
    <script src="{{ asset('admin/js/exposicion.js') }}"></script>
@endsection
@section('breadcrumb')
	<li class="breadcrumb-item">
		<a href="{{route('sedes.index')}}"> Sedes </a>
	</li>
	<li class="breadcrumb-item">
		 Nueva sede
	</li>
@endsection
@section('content')
<div class="row justify-content-center">
	<div class="col-md-6">
		<div class="card card-accent-info">
		  <h5 class="card-header">Sede</h5>
		  <div class="card-body">
			{!! Form::open(['route' => 'sedes.store']) !!}
			@include('movimiento.sede._form')
			<div class="text-center mb-3">
				<a href="{{route('sedes.index')}}" class="btn btn-secondary">Cancelar</a>
				{!! Form::submit('Crear sede', ['class'=>'btn btn-primary']) !!}
			</div>
			{!! Form::close() !!}
		  </div>
		</div>
	</div>
</div>
@endsection
