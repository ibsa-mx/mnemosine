@extends('admin.layout')

@section('title', '- Movimientos, Paso 4 - Regresar Piezas')

@section('assets')
	<script src="{{ asset('admin/js/movimientosPaso4.js') }}"></script>
@endsection

@section('breadcrumb')
	<li class="breadcrumb-item">
		<a href="{{route('movimientos.index')}}">Movimientos</a>
	</li>
	<li class="breadcrumb-item">
		Paso 4 - Regresar piezas
	</li>
@endsection

@section('content')
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

{!! Form::open(['method' => 'PUT', 'route' => ['movimientos.update', $movimiento->id], 'id' => 'frmRetrieval']) !!}
	{{-- update step 4 --}}
	<input type="hidden" name="p4" value="4"/>
	<input type="hidden" name="count_pieces" id="count_pieces" value="{{$piezas->count() - count($piezasDiff)}}"/>
	<input type="hidden" name="count_locations" id="count_locations" value="0"/>
	<input type="hidden" name="count_selected" id="count_selected" value="0"/>
	<input type="hidden" name="location_numbers" id="location_numbers" value=""/>
	<input type="hidden" name="location_numbers_deleted" id="location_numbers_deleted" value=""/>
	@if(!is_null($piezas))
		@foreach($piezas as $pieza)
			@continue(in_array($pieza->id, $piezasDiff))
			<input type="hidden" class="hidden-piece-fields" id="hidden-piece-{{$pieza->id}}" data-piece-id="{{$pieza->id}}" value="0" />
		@endforeach
	@endif

	<div class="alert alert-info">
		<i class="fas fa-info-circle"></i> Seleccione las ubicaciones a las que regresan las piezas de este movimiento
	</div>
	<div class="content">
		@if (isset($arrivalInformation) && !is_null($arrivalInformation))
			<div class="row border border-primary mb-2">
				<div class="col-12 bg-primary">
					<strong>Previamente regresadas</strong>
				</div>
				@foreach ($arrivalInformation as $key => $info)
					<div class="col-12">
						<div class="row bg-light my-1 mx-1 py-1 callout callout-info">
							<div class="col-12">
								<small class="text-muted">Piezas</small>
								<br/>
								@foreach ($info->pieces as $key => $piece)
									<span class="badge badge-warning">{{$piezasByKey[$piece]->inventory_number}} / {{$piezasByKey[$piece]->catalog_number}}</span>
								@endforeach
							</div>
							<div class="col-6">
								<small class="text-muted">Ubicación</small>
								<br/>
								<strong class="h6">
									@if ($info->location == 0)
										<strong class='text-danger'>En prestamo</strong>
									@else
										{{$ubicacionesByKey[$info->location]->name}}
									@endif
								</strong>
							</div>
							{{-- <div class="col-4">
								<small class="text-muted">Mueble</small>
								<br/>
								@foreach ($info->tags as $key => $tag)
									<span class="badge badge-warning">{{$tag}}</span>
								@endforeach
							</div> --}}
							<div class="col-6">
								<small class="text-muted">Fecha</small>
								<br/>
								<strong class="h6">{{\Carbon\Carbon::createFromFormat('Y-m-d', $info->arrival_date)->locale('es_MX')->isoFormat('LL')}}</strong>
							</div>
						</div>
					</div>
				@endforeach
			</div>
		@endif
		<div id="location-elements" class="d-none">
			<div class="rounded bg-gray-400 mb-3 p-2">
				<div class="lead mb-2 text-light">
					Ubicación <span class="span-ubicacion"></span>
					<button class="btn btn-sm btn-danger location_delete float-right"><i class="far fa-trash-alt"></i> Eliminar ubicación</button>
				</div>
				<div class="input-group mb-3">
					<div class="input-group-prepend">
						<span class="input-group-text">Piezas</span>
					</div>
				    <select class="form-control form-group select-piezas" multiple="multiple">
				    	@if(!is_null($piezas))
				    		@foreach($piezas as $pieza)
								@continue(in_array($pieza->id, $piezasDiff))
				    			<option value="{{$pieza->id}}" class="option-{{$pieza->id}}">{{$pieza->inventory_number}} / {{$pieza->catalog_number}}</option>
				    		@endforeach
				    	@endif
				    </select>
				</div>
				<div class="row no-gutters">
					<div class="col-12 col-md-6 pr-1">
						<div class="input-group">
							<div class="input-group-prepend">
								<span class="input-group-text">Ubicación</span>
							</div>
						    <select class="form-control form-group select-ubicacion">
						    	@if(!is_null($ubicaciones))
						    		@foreach($ubicaciones as $ubicacion)
						    			<option value="{{$ubicacion->id}}">{{$ubicacion->name}}</option>
						    		@endforeach
						    	@endif
						    </select>
						</div>
					</div>
					{{-- <div class="col-12 col-md-4 pr-1">
						<div class="input-group">
						    <div class="input-group-prepend">
						        <span class="input-group-text">Mueble</span>
						    </div>
						    <select class="form-control select-mueble" multiple="multiple">
						    </select>
						</div>
					</div> --}}
					<div class="col-12 col-md-6">
						<div class="input-group">
							<div class="input-group-prepend">
								<span class="input-group-text">Fecha</span>
							</div>
						    {!! Form::text('', today()->format('Y-m-d'), ['class' => 'form-control daterange-single text-arrival-date']) !!}
						</div>
					</div>
				</div>
			</div>
		</div>
		<div id="location-clones"></div>

		<div class="text-center">
			<button class="btn btn-primary" id="btn-add-location">
				<i class="fas fa-plus"></i> Agregar ubicación
			</button>
			<br/>
			<button class="btn btn-lg btn-success mt-3" id="btn-submit">
				<i class="far fa-check-circle"></i> Registrar el regreso de las piezas
			</button>
		</div>
	</div>
{!! Form::close() !!}
@endsection
