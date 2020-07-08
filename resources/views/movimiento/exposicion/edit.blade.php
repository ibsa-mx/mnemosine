@extends('admin.layout')
@section('title', '-Exposiciones, Editando exposiciones')
@section('assets')
    <script src="{{ asset('admin/js/exposicion.js') }}"></script>
@endsection
@section('breadcrumb')
	<li class="breadcrumb-item">
            <a href="{{route('exposiciones.index')}}">Exposiciones</a>
    </li>
    <li class="breadcrumb-item active">
        Editando exposición
    </li>
@endsection
@section('content')
<div class="row justify-content-center">
	<div class="col-md-6">
		<div class="card card-accent-info">
		  <h5 class="card-header">Exposición</h5>
		  <div class="card-body">
			{!! Form::model($exposicion, ['method' => 'PUT', 'route' => ['exposiciones.update', $exposicion->id], 'id' => 'frmExposicion']) !!}
				@include('movimiento.exposicion._form')

			<div class="text-center mb-3">
	            <a href="{{ route('exposiciones.index') }}" class="btn btn-secondary">Cancelar</a>
	            {!! Form::submit('Guardar cambios', ['class' => 'btn btn-primary']) !!}
	        </div>

			{!! Form::close() !!}
		  </div>
		</div>
	</div>
</div>
@endsection
