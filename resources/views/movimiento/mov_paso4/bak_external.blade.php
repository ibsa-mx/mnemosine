@extends('admin.layout')
@section('title', '-Movimientos, Paso 4 -Regresar piezas')
@section('breadcrumb')
	<li class="breadcrumb-item">
		<a href="{{route('movimientos.index')}}">Movimientos</a>
	</li>
	<li class="breadcrumb-item">
		Regresar Piezas
	</li>
@endsection

@section('content')

<div class="d-flex justify-content-center">
	<div class="card">
	  <div class="card-header">
	    Regreso total de las piezas
	  </div>
	  <div class="card-body">
	  	@if (count($errors) > 0)
		    <div class="alert alert-danger">
		        Hubo problemas con los datos ingresados.<br/><br/>
		        <ul>
		            @foreach ($errors->all() as $error)
		                <li>{{ $error }}</li>
		            @endforeach
		        </ul>
		    </div>
		@endif
	    {!! Form::model($movimiento, ['method' => 'PUT', 'route' => ['movimientos.update', $movimiento->id], 'id' => 'frmRegresarAll']) !!}
			<input type="hidden" name="p4" value="4">
			<div class="input-group mb-3 @error('arrival_date') has-error @enderror">
				<div class="input-group-prepend">
					<span class="input-group-text">Fecha de regreso</span>
				</div>
				<input type="date" name="arrival_date" class="form-control @error('arrival_date') is-invalid @enderror">
				@error('arrival_date')
			        <div class="invalid-feedback">
			            {{ $errors->first('arrival_date') }}
			        </div>
			    @enderror
			</div>
			<div class="mb-3 @error('arrival_location_id') has-error @enderror">
				<label>Almacen de regreso</label>
			    <select class="form-control select2 form-group @error('arrival_location_id') is-invalid @enderror js-example-basic-single" name="arrival_location_id" required="required">
			    	@if($ubicaciones != null)
			    		<option selected="selected" disabled="disabled">Seleccione una opción</option>
						@foreach($ubicaciones as $item)
							<option value="{{$item->id}}">{{$item->title}}</option>
						@endforeach
					@endif
				    @error('arrival_location_id')
				        <div class="invalid-feedback">
				            {{ $errors->first('arrival_location_id') }}
				        </div>
				    @enderror
				</select>
			</div>
			<div class="d-flex justify-content-center">
				<button class="btn btn-primary" type="submit">Confirmar</button>
			</div>

		{!! Form::close() !!}
	  </div>
	</div>
</div>
@endsection
