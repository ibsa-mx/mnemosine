<!-- Inventario -->
    <div class="card card-accent-info">
        <div class="card-header h4" role="tab">
            <a href="#" data-toggle="collapse" data-target="#collapseInventario">
                Inventario
            </a>
            <div class="card-header-actions">
                @can('editar_inventario')
                <a href="{{ route('inventario.edit', $piece->id) }}" class="card-header-action btn-minimize" rel="tooltip" title="Editar pieza">
                    <i class="icon-pencil"></i>
                </a>
                @endcan
                <a class="card-header-action btn-minimize" href="#" data-toggle="collapse" data-target="#collapseInventario" aria-expanded="true" rel="tooltip" title="Mostrar u ocultar">
                    <i class="icon-arrow-up"></i>
                </a>
            </div>
        </div>
        <div id="collapseInventario" class="collapse show">
            <div class="card-body py-2">
                <div class="row">
                    <div class="col-md">
                        <span class="label">No. inventario:</span>
                        {{$piece->inventory_number}}
                    </div>
                    <div class="col-md">
                        <span class="label">No. catálogo:</span>
                        {{$piece->catalog_number}}
                    </div>
                    <div class="col-md">
                        <span class="label">No. procedencia:</span>
                        {{$piece->origin_number}}
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        @include('shared._big-text', [
                            'btId' => 'description_origin',
                            'btLabel' => 'Descripción',
                            'btText' => $piece->description_origin
                        ])
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col">
                        <span class="label">Género:</span>
                        {!!isset($piece->gender->title)? $piece->gender->title: "<em>N/A</em>"!!}
                    </div>
                    <div class="col">
                        <span class="label">Subgénero:</span>
                        {!!isset($piece->subgender->title)? $piece->subgender->title: "<em>N/A</em>"!!}
                    </div>
                    <div class="col">
                        <span class="label">Tipo de objeto:</span>
                        {!!isset($piece->type_object->title)? $piece->type_object->title: "<em>N/A</em>"!!}
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col">
                        <span class="label">Mueble:</span>
                        @isset($piece->tags)
                            @foreach (explode(",", $piece->tags) as $tag)
                                <a href="{{route('consultas.search', $tag)}}" class="badge badge-warning">{{$tag}}</a>
                            @endforeach
                        @endisset
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <span class="label">Avalúo:</span>
                        {!!($piece->appraisal)? money($piece->appraisal) . ' USD' : "<em>N/A</em>"!!}
                        <button type="button" class="btn btn-link p-0 mb-3" data-toggle="modal" data-target="#appraisalModal">
                            <i class="fas fa-history"></i> Ver historial de avalúo
                        </button>
                    </div>
                    <div class="col">
                        <span class="label">Ubicación:</span>
                        {!! ($piece->location_id == 0) ? "<strong class='text-danger'>En préstamo</strong>" : (!isset($piece->location->name) ? "<em>N/A</em>" : $piece->location->name) !!}
                    </div>
                    <div class="col">
                        <span class="label">Fecha de {!! (isset($movements) && !is_null($movements[0]->arrival_date)) ? "entrada" : "salida" !!}:</span>
                        @isset($movements)
                            @if (!is_null($movements[0]->arrival_date))
                                {{$movements[0]->arrival_date->locale('es_MX')->isoFormat('LL')}}
                            @else
                                {{$movements[0]->departure_date->locale('es_MX')->isoFormat('LL')}}
                            @endif
                        @else
                            <em>N/A</em>
                        @endisset
                    </div>
                </div>
                @php
                    if($piece->base_or_frame == 'base'){
                        $measureType = 'base';
                    } elseif($piece->base_or_frame == 'frame'){
                        $measureType = 'marco';
                    }
                @endphp
                <div class="row border border-primary mb-2">
                    <div class="col-12 bg-primary">
                        <strong>Medidas <em>sin {{$measureType}}</em> (cm)</strong>
                    </div>
                    <div class="col">
                        <span class="label">Alto</span>
                        <div>{{$piece->height}}</div>
                    </div>
                    <div class="col">
                        <span class="label">Ancho</span>
                        <div>{{$piece->width}}</div>
                    </div>
                    <div class="col">
                        <span class="label">Profundo</span>
                        <div>{{$piece->depth}}</div>
                    </div>
                    <div class="col">
                        <span class="label">Diámetro</span>
                        <div>{{$piece->diameter}}</div>
                    </div>
                </div>
                <div class="row border border-primary mb-2">
                    <div class="col-12 bg-primary">
                        <strong>Medidas <em>con {{$measureType}}</em> (cm)</strong>
                    </div>
                    <div class="col">
                        <span class="label">Alto</span>
                        <div>{{$piece->height_with_base}}</div>
                    </div>
                    <div class="col">
                        <span class="label">Ancho</span>
                        <div>{{$piece->width_with_base}}</div>
                    </div>
                    <div class="col">
                        <span class="label">Profundo</span>
                        <div>{{$piece->depth_with_base}}</div>
                    </div>
                    <div class="col">
                        <span class="label">Diámetro</span>
                        <div>{{$piece->diameter_with_base}}</div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6 text-center text-muted">
                        Creado por <strong>{{$piece->created_by_name}}</strong>,
                        <span rel="tooltip" title="{{ $piece->created_at->locale('es_MX')->isoFormat('LLLL') }}">{{$piece->created_at->diffForHumans()}}</span>
                    </div>
                    <div class="col-6 text-center text-muted">
                        Modificado por <strong>{{$piece->updated_by_name}}</strong>,
                        <span rel="tooltip" title="{{ $piece->updated_at->locale('es_MX')->isoFormat('LLLL') }}">{{$piece->updated_at->diffForHumans()}}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
