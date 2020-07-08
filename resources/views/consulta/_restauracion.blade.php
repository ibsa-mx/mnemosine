<!-- Restauracion -->
    <div class="card card-accent-info">
        <div class="card-header h4" role="tab">
            <a href="#" data-toggle="collapse" data-target="#collapseRestauracion">
                Restauración
            </a>
            <div class="card-header-actions">
                @can('agregar_restauracion')
                <a href="{{ route('restauracion.create', $piece->id) }}" class="card-header-action btn-setting" rel="tooltip" title="Agregar restauración">
                    <i class="icon-plus"></i>
                </a>
                @endcan
                @can('editar_restauracion')
                <a href="{{ route('restauracion.listRecords', $piece->id) }}" class="card-header-action btn-setting" rel="tooltip" title="Editar historial de restauración">
                    <i class="icon-pencil"></i>
                </a>
                @endcan
                <a class="card-header-action btn-minimize" href="#" data-toggle="collapse" data-target="#collapseRestauracion" aria-expanded="true" rel="tooltip" title="Mostrar u ocultar">
                    <i class="icon-arrow-up"></i>
                </a>
            </div>
        </div>
        <div id="collapseRestauracion" class="collapse show">
            <div class="card-body pb-0 pt-3">
            @isset($restorations)
                @foreach ($restorations as $idx => $restoration)
                <div class="row border border-success mb-3">
                    <div class="col-12 bg-success py-1">
                        <a href="#" class="text-white stretched-link" data-toggle="collapse" data-target="#collapseRestoration-{{$restoration->id}}">
                            <strong>{{$restoration->treatment_date->locale('es_MX')->isoFormat('LL')}}</strong>
                        </a>
                        <div class="card-header-actions">
                            <a class="card-header-action btn-minimize text-white" href="#" data-toggle="collapse" data-target="#collapseRestoration-{{$restoration->id}}" aria-expanded="true" rel="tooltip" title="Mostrar u ocultar">
                                <i class="icon-arrow-up"></i>
                            </a>
                        </div>
                    </div>
                    <div class="col-12 collapse" id="collapseRestoration-{{$restoration->id}}">
                        <div class="row mb-2">
                            <div class="col-12">
                                @include('shared._big-text', [
                                    'btId' => 'preliminary_examination',
                                    'btLabel' => 'Exámen preliminar',
                                    'btText' => $restoration->preliminary_examination
                                ])
                            </div>
                            <div class="col-12">
                                @include('shared._big-text', [
                                    'btId' => 'laboratory_analysis',
                                    'btLabel' => 'Análisis de laboratorio',
                                    'btText' => $restoration->laboratory_analysis
                                ])
                            </div>
                            <div class="col-12">
                                @include('shared._big-text', [
                                    'btId' => 'proposal_of_treatment',
                                    'btLabel' => 'Propuesta de tratamiento',
                                    'btText' => $restoration->proposal_of_treatment
                                ])
                            </div>
                            <div class="col-12">
                                @include('shared._big-text', [
                                    'btId' => 'treatment_description',
                                    'btLabel' => 'Descripción de tratamiento',
                                    'btText' => $restoration->treatment_description
                                ])
                            </div>
                            <div class="col-12">
                                @include('shared._big-text', [
                                    'btId' => 'results',
                                    'btLabel' => 'Resultado y recomendaciones',
                                    'btText' => $restoration->results
                                ])
                            </div>
                            <div class="col-12">
                                @include('shared._big-text', [
                                    'btId' => 'observations',
                                    'btLabel' => 'Observaciones',
                                    'btText' => $restoration->observations
                                ])
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col">
                                <span class="label">Fecha de tratamiento:</span>
                                <span rel="tooltip" title="{{ $restoration->treatment_date->locale('es_MX')->isoFormat('LL') }}">{{$restoration->treatment_date->diffForHumans()}}</span>
                            </div>
                            <div class="col">
                                <span class="label">Restaurador responsable:</span>
                                {{($responsibleRestorers) ? $responsibleRestorers->pluck('title', 'id')->get($restoration->responsible_restorer) : 'N/A'}}
                            </div>
                        </div>
                        @php
                            if($restoration->base_or_frame == 'base'){
                                $measureType = 'base';
                            } elseif($restoration->base_or_frame == 'frame'){
                                $measureType = 'marco';
                            }
                        @endphp
                        <div class="row border border-primary mb-2 mx-0">
                            <div class="col-12 bg-primary">
                                <strong>Medidas <em>sin {{$measureType}}</em> (cm)</strong>
                            </div>
                            <div class="col">
                                <span class="label">Alto</span>
                                <div>{{$restoration->height}}</div>
                            </div>
                            <div class="col">
                                <span class="label">Ancho</span>
                                <div>{{$restoration->width}}</div>
                            </div>
                            <div class="col">
                                <span class="label">Profundo</span>
                                <div>{{$restoration->depth}}</div>
                            </div>
                            <div class="col">
                                <span class="label">Diámetro</span>
                                <div>{{$restoration->diameter}}</div>
                            </div>
                        </div>
                        <div class="row border border-primary mb-2 mx-0">
                            <div class="col-12 bg-primary">
                                <strong>Medidas <em>con {{$measureType}}</em> (cm)</strong>
                            </div>
                            <div class="col">
                                <span class="label">Alto</span>
                                <div>{{$restoration->height_with_base}}</div>
                            </div>
                            <div class="col">
                                <span class="label">Ancho</span>
                                <div>{{$restoration->width_with_base}}</div>
                            </div>
                            <div class="col">
                                <span class="label">Profundo</span>
                                <div>{{$restoration->depth_with_base}}</div>
                            </div>
                            <div class="col">
                                <span class="label">Diámetro</span>
                                <div>{{$restoration->diameter_with_base}}</div>
                            </div>
                        </div>
                        {{-- Documentos relacionados --}}
                        <div class="row border border-primary mb-2 mx-0">
                            <div class="col-12 bg-primary">
                                <strong>Documentos asociados a esta pieza</strong>
                            </div>
                            <div class="col-12">
                                <div class="row">
                                    @php
                                        $validDocumentIds = !empty($restoration->documents_ids) ? explode(",", $restoration->documents_ids) : null;
                                    @endphp
                                    @if(isset($piece->documents) && $piece->documents->count() > 0 && !is_null($validDocumentIds))
                                        @foreach ($piece->documents as $idx => $document)
                                            @if (!in_array($document->id, $validDocumentIds))
                                                @continue
                                            @endif
                                            <div class="col-sm-12 col-md-6 p-1 my-0">
                                                <div class="card my-0 text-white bg-{{$bgColors[$fileTypes[$document->mime_type]]}}">
                                                    <div class="card-body">
                                                        <div class="row position-static">
                                                            <div class="col-2 position-static">
                                                                <a href="{{$storageUrl['restoration']}}{{$document->file_name}}" class="stretched-link text-white" target="_blank"><i class="far fa-file-{{$mimeIcons[$document->mime_type]}} fa-4x"></i></a>
                                                            </div>
                                                            <div class="col-10 position-static">
                                                                <div class="text-value">{{$document->name}}</div>
                                                                <div>
                                                                    Tamaño: {{formatBytes($document->size)}} | Tipo: {{$fileTypes[$document->mime_type]}}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                        <div class="col-12">
                                            <div class="alert alert-warning mt-3">No se han asociado documentos</div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-6 text-center text-muted">
                                Creado por <strong>{{$restoration->created_by_name}}</strong>,
                                <span rel="tooltip" title="{{ $restoration->created_at->locale('es_MX')->isoFormat('LLLL') }}">{{$restoration->created_at->diffForHumans()}}</span>
                            </div>
                            <div class="col-6 text-center text-muted">
                                Modificado por <strong>{{$restoration->updated_by_name}}</strong>,
                                <span rel="tooltip" title="{{ $restoration->updated_at->locale('es_MX')->isoFormat('LLLL') }}">{{$restoration->updated_at->diffForHumans()}}</span>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            @else
                <div class="alert alert-warning mt-3">No se han registrado datos de restauración</div>
            @endisset
            </div>
        </div>
    </div>
