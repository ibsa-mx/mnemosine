@extends('admin.layout')
@section('title', '-Sedes, Editando sede')
@section('assets')
    <script src="{{ asset('admin/js/exposicion.js') }}"></script>
@endsection
@section('breadcrumb')
	<li class="breadcrumb-item">
		<a href="{{route('sedes.index')}}">Sedes</a>
	</li>
	<li class="breadcrumb-item">
		Editando sede
	</li>
@endsection

@section('content')
<div class="row justify-content-center">
	<div class="col-md-6">
		<div class="card card-accent-info">
		  <h5 class="card-header">Sede</h5>
		  <div class="card-body">
			{!! Form::model($sede, ['method' => 'PUT', 'route' => ['sedes.update', $sede->id], 'id' => 'frmSede']) !!}
			@include('movimiento.sede._form')

			<div class="text-center mb-3">
			    <a href="{{ route('sedes.index') }}" class="btn btn-secondary">Cancelar</a>
			    {!! Form::submit('Guardar cambios', ['class' => 'btn btn-primary']) !!}
			</div>

			{!! Form::close() !!}
	  	  </div>
		</div>
	</div>
</div>
@endsection
