	<div class="card card-accent-info">
		<div class="card-header lead">Datos generales</div>
		<div class="card-body p-0 m-0">
			<table class="table table-striped">
				<tbody>
					<tr>
						<td scope="row">Tipo de movimiento: </td>
						<td>
							@if($movimiento->movement_type == "external")
								Externo
							@else
								Interno
							@endif
						</td>
						<td scope="row">Itinerante:</td>
						<td>
							@if(isset($movimiento->itinerant) && $movimiento->itinerant == '0')
								No
							@else
								Si
							@endif
						</td>
					</tr>
					<tr>
						<td scope="">Institución(es):</td>
						<td>
	                        @if($instituciones != null)
	                            @php
	                            $institucionesStr = $instituciones->pluck('name')->join(', ');
	                            @endphp
	                            {{$institucionesStr}}
	                        @endif
	                    </td>
						<td scope="">Contacto(s):</td>
						<td>
	                        @if($contactos != null)
	                            @php
	                            $contactosNombres = $contactos->pluck('name', 'id')->toArray();
	                            $contactosApellidos = $contactos->pluck('last_name', 'id')->toArray();
	                            $contactosArr = array();
	                            foreach($contactosNombres as $idx => $nombre){
	                                $contactosArr[] = $nombre . " " . $contactosApellidos[$idx];
	                            }
	                            $contactosStr = implode(", ", $contactosArr);
	                            @endphp
	                            {{$contactosStr}}
	                        @endif
	                    </td>
					</tr>
					<tr>
						<td scope="row">Ubicación / Exposición:</td>
						<td>{{($expo)? $expo->name: 'N/A'}}</td>
						<td scope="row">Sede(s):</td>
						<td>
							@isset($sedes)
								{!! implode(", ", array_column($sedes, 'name')) !!}
							@else
								N/A
							@endisset
						</td>

					</tr>
					<tr>
						<td scope="row">Fecha de salida:</td>
						<td>{{($movimiento->departure_date)? $movimiento->departure_date->locale('es_MX')->isoFormat('LL'): 'N/A'}}</td>
						<td>Fechas de exhibición:</td>
						<td>{{$movimiento->start_exposure ? $movimiento->start_exposure->locale('es_MX')->isoFormat('LL') : '-'}} al {{$movimiento->end_exposure ? $movimiento->end_exposure->locale('es_MX')->isoFormat('LL') : '-'}}</td>
					</tr>
					<tr>
						<td scope="row">Observaciones:</td>
						<td colspan="3">{{($movimiento->observations)? $movimiento->observations: 'N/A' }}</td>
					</tr>
					<tr>
						<td scope="row">Autorizado por:</td>
						<td colspan="3">
							@if($movimiento->authorized_by_collections != null)
								Departamento de investigación,
							@elseif($movimiento->authorized_by_exhibitions != null)
								Departamento de Restauración,
							@endif
							{{isset($movimiento->authorized_by_user) ? $movimiento->authorized_by_user->name : 'N/A'}}
						</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
	<div class="card card-accent-info">
		<div class="card-header lead">
			<span class="badge badge-info" id="piezasCargadas">
				@if(isset($piezas) && ($piezas!=null))
					{!! count($piezas) !!}
				@else
					0
				@endif
			</span>
			Piezas cargadas
		</div>
		<div class="card-body p-0 m-0">
			<table class="table table-striped">
				<thead>
					<tr>
						<th>No. inventario</th>
						<th>No. catálogo</th>
						<th>No. procedencia</th>
						<th>Descripción</th>
						<th>Ubicación</th>
						<th>Foto de inventario</th>
					</tr>
				</thead>
				<tbody>
					@if(is_array($piezas) || is_object($piezas))
						@foreach($piezas as $pieza)
							<tr>
								<td>
									<a href="{{route('consultas.detalle', $pieza->id)}}" target="_blank">{{$pieza->inventory_number}}</a>
								</td>
								<td>{{$pieza->catalog_number}}</td>
								<td>{{$pieza->origin_number}}</td>
								<td>{{Str::limit($pieza->description_origin, 100)}}</td>
								<td>{{isset($pieza->location) ? $pieza->location->name : 'En préstamo'}}</td>
								<td>
									@isset($pieza->photography)
									    @php
									    $aux = 0;
									    @endphp
									    @foreach ($pieza->photography as $photography)
									        @continue($photography['module_id'] != 1)
									        <a href="{!! Storage::url('') !!}{!! config('fileuploads.inventory.photographs.originals') !!}/{{$photography['file_name']}}" class="{{ $aux++ == 0 ? '' : 'd-none' }}" data-toggle="lightbox" data-gallery="gallery-1-{{$photography['piece_id']}}" data-title="{{$photography['description']}}" data-footer="Fotógrafo: {{$photography['photographer']}}<br>Fecha: {{$photography['photographed_at']->locale('es_MX')->isoFormat('LL')}}">
									            <img src="{!! Storage::url('') !!}{!! config('fileuploads.inventory.photographs.thumbnails') !!}/{{$photography['file_name']}}" class="img-thumbnail" alt="{{$photography['description']}}">
									        </a>
									    @endforeach
									@endisset
								</td>
							</tr>
						@endforeach
					@endif
				</tbody>
			</table>
		</div>
	</div>

	@if($movimiento->pieces_ids_arrived != null)
		<div class="card card-accent-info">
			<div class="card-header lead">
				<span class="badge badge-info" id="piezasRegresadas">
					@if(isset($piezas_r) && ($piezas_r!=null))
						{!! count($piezas_r) !!}
					@else
						0
					@endif
				</span>
				Piezas regresadas
			</div>
			<div class="card-body">
				@if(is_array($piezas_r) || is_object($piezas_r))
					@foreach($piezas_r as $pieza)
						<a href="{{route('consultas.detalle', $pieza->id)}}" target="_blank" class="badge badge-warning">{{$pieza->inventory_number}} / {{$pieza->catalog_number}}</a>
					@endforeach
				@endif
			</div>
		</div>
	@endif

@include('flash-toastr::message')

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
