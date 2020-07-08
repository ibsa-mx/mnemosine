<!-- Investigacion -->
    <div class="card card-accent-info">
        <div class="card-header h4" role="tab">
            <a href="#" data-toggle="collapse" data-target="#collapseInvestigacion">
                Investigación
            </a>
            <div class="card-header-actions">
                @can('editar_investigacion')
                <a href="{{ route('investigacion.edit', $piece->id) }}" class="card-header-action btn-minimize" rel="tooltip" title="Editar información">
                    <i class="icon-pencil"></i>
                </a>
                @endcan
                <a class="card-header-action btn-minimize" href="#" data-toggle="collapse" data-target="#collapseInvestigacion" aria-expanded="true" rel="tooltip" title="Mostrar u ocultar">
                    <i class="icon-arrow-up"></i>
                </a>
            </div>
        </div>
        <div id="collapseInvestigacion" class="collapse show">
            <div class="card-body py-2">
            @isset($piece->research)
                <div class="row mb-2">
                    <div class="col">
                        <span class="label">Título:</span>
                        {{($piece->research)? $piece->research->title: 'N/A'}}
                    </div>
                    <div class="col">
                        <span class="label">Autor(es):</span>
                        {{($authorNames)? $authorNames->join(", "): 'N/A'}}
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col">
                        <span class="label">Conjunto:</span>
                        {{isset($piece->research->set->title)? $piece->research->set->title: 'N/A'}}
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-12">
                        @include('shared._big-text', [
                            'btId' => 'technique',
                            'btLabel' => 'Técnica',
                            'btText' => $piece->research->technique
                        ])
                    </div>
                    <div class="col-12">
                        @include('shared._big-text', [
                            'btId' => 'materials',
                            'btLabel' => 'Materiales',
                            'btText' => $piece->research->materials
                        ])
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col">
                        <span class="label">Procedencia:</span>
                        {{isset($piece->research->place_of_creation->title)? $piece->research->place_of_creation->title: 'N/A'}}
                    </div>
                    <div class="col">
                        <span class="label">Fecha de creación:</span>
                        {{($piece->research->creation_date)? $piece->research->creation_date: 'N/A'}}
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col">
                        <span class="label">Época:</span>
                        {{isset($piece->research->period->title)? $piece->research->period->title: 'N/A'}}
                    </div>
                </div>
                <div class="row border border-primary mb-2">
                    <div class="col-12 bg-primary">
                        <strong>Proveniencia</strong>
                    </div>
                    <div class="col-12 my-2">
                        <span class="label">Forma:</span>
                        {{($piece->research->acquisition_form)? $piece->research->acquisition_form: 'N/A'}}
                    </div>
                    <div class="col-12 mb-2">
                        <span class="label">Fuente/lugar:</span>
                        {{($piece->research->acquisition_source)? $piece->research->acquisition_source: 'N/A'}}
                    </div>
                    <div class="col-12 mb-2">
                        <span class="label">Fecha:</span>
                        {{($piece->research->acquisition_date)? $piece->research->acquisition_date: 'N/A'}}
                    </div>
                </div>
                <div class="row border border-primary mb-2">
                    <div class="col-12 bg-primary">
                        <strong>Firmas o marcas</strong>
                    </div>
                    <div class="col-1 d-flex align-items-center justify-content-center">
                        <strong>{!! ((bool)$piece->research->firm ? 'Sí' : 'No') !!}</strong>
                    </div>
                    <div class="col-11">
                        @include('shared._big-text', [
                            'btId' => 'description_signature',
                            'btLabel' => 'Descripción',
                            'btText' => $piece->research->firm_description
                        ])
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        @include('shared._big-text', [
                            'btId' => 'short_description',
                            'btLabel' => 'Descripción abreviada',
                            'btText' => $piece->research->short_description
                        ])
                    </div>
                    <div class="col-12">
                        @include('shared._big-text', [
                            'btId' => 'formal_description',
                            'btLabel' => 'Descripción formal',
                            'btText' => $piece->research->formal_description
                        ])
                    </div>
                    <div class="col-12">
                        @include('shared._big-text', [
                            'btId' => 'observations',
                            'btLabel' => 'Observaciones',
                            'btText' => $piece->research->observation
                        ])
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        @isset($piece->research->publications)
                            @include('shared._big-text', [
                                'btId' => 'publications',
                                'btLabel' => 'Publicaciones en las que aparece la obra',
                                'btText' => $piece->research->publications
                            ])
                        @else
                            <div class="alert alert-warning mt-3">No se han registrado publicaciones</div>
                        @endisset
                    </div>
                </div>
                <div class="row border border-primary mb-2">
                    <div class="col-12 bg-primary mb-3">
                        <strong>Notas al pie</strong>
                    </div>
                    @if(!is_null($footnotes))
                        @foreach ($footnotes as $idx => $footnote)
                            <div class="col-12">
                                    @include('consulta._footnotes', [
                                        'footnote' => $footnote
                                    ])
                            </div>
                        @endforeach
                    @else
                    <div class="col-12">
                        <div class="alert alert-warning mt-3">No se registraron notas al pie</div>
                    </div>
                    @endif
                </div>
                <div class="row border border-primary mb-2">
                    <div class="col-12 bg-primary mb-3">
                        <strong>Bibliografía</strong>
                    </div>
                    @if(!is_null($bibliographs))
                        @foreach ($bibliographs as $idx => $bibliography)
                            <div class="col-12">
                                    @include('consulta._references', [
                                        'bibliography' => $bibliography
                                    ])
                            </div>
                        @endforeach
                    @else
                    <div class="col-12">
                        <div class="alert alert-warning mt-3">No se ha registrado bibliografía</div>
                    </div>
                    @endif
                </div>
                {{-- Documentos relacionados --}}
                <div class="row border border-primary mb-2">
                    <div class="col-12 bg-primary">
                        <strong>Documentos asociados a esta pieza</strong>
                    </div>
                    <div class="col-12">
                        <div class="row p-1">
                            @if(isset($documents) && $documents->count() > 0)
                                @foreach ($documents as $idx => $document)
                                    @if ($modulesId['investigacion']->id != $document->module_id)
                                        @continue
                                    @endif
                                    <div class="col-12 col-md-6 p-1 my-0">
                                        <div class="card my-0 text-white bg-{{$bgColors[$fileTypes[$document->mime_type]]}}">
                                            <div class="card-body">
                                                <div class="row position-static">
                                                    <div class="col-2 position-static">
                                                        <a href="{{$storageUrl['research']}}{{$document->file_name}}" class="stretched-link text-white" target="_blank"><i class="far fa-file-{{$mimeIcons[$document->mime_type]}} fa-4x"></i></a>
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
                    <div class="col">
                        <span class="label">Cédula:</span>
                        {{($piece->research)? $piece->research->card: 'N/A'}}
                    </div>
                </div>
                <div class="row">
                    <div class="col-6 text-center text-muted">
                        Creado por <strong>{{$piece->research->created_by_name}}</strong>,
                        <span rel="tooltip" title="{{ $piece->research->created_at->locale('es_MX')->isoFormat('LLLL') }}">{{$piece->research->created_at->diffForHumans()}}</span>
                    </div>
                    <div class="col-6 text-center text-muted">
                        Modificado por <strong>{{$piece->research->updated_by_name}}</strong>,
                        <span rel="tooltip" title="{{ $piece->research->updated_at->locale('es_MX')->isoFormat('LLLL') }}">{{$piece->research->updated_at->diffForHumans()}}</span>
                    </div>
                </div>
            @else
                <div class="alert alert-warning mt-3">No se han registrado datos de investigación</div>
            @endisset
            </div>
        </div>
    </div>
