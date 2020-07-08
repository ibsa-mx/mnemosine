<div class="card card-accent-info">
    <div class="card-header" role="tab">
        <a href="#" data-toggle="collapse" data-target="#collapse{{$module}}Imagenes">
            <strong>Imagenes de {{$label}}</strong>
        </a>
        <div class="card-header-actions">
            <a class="card-header-action btn-minimize" href="#" data-toggle="collapse" data-target="#collapse{{$module}}Imagenes" aria-expanded="true" rel="tooltip" title="Mostrar u ocultar">
                <i class="icon-arrow-up"></i>
            </a>
        </div>
    </div>
    <div id="collapse{{$module}}Imagenes" class="collapse show">
        <div class="card-body card-img py-2">
            @if(isset($piece->photography))
                <div id="carousel{{$module}}" class="carousel slide carousel-fade" data-ride="carousel">
                    <div class="carousel-inner align-self-center">
                        @php
                        $aux = 0;
                        @endphp
                        @foreach($piece->photography as $photography)
                            @if ($modulesId[strtolower($module)]->id != $photography->module_id)
                                @continue
                            @endif
                            <div class="carousel-item text-center{{ $aux++ == 0 ? ' active' : '' }}">
                                <a href="{!! Storage::url('') !!}{!! config('fileuploads.'. $conf .'.photographs.originals') !!}/{{$photography->file_name}}" data-toggle="lightbox" data-gallery="gallery{{$module}}" data-title="{{$photography->description}}" data-footer="Fotógrafo: {{$photography->photographer}}<br>Fecha: {{ $photography->photographed_at->locale('es_MX')->isoFormat('LL') }}">
                                    <img src="{!! Storage::url('') !!}{!! config('fileuploads.'. $conf .'.photographs.thumbnails') !!}/{{$photography->file_name}}" class="img-thumbnail"  alt="">
                                </a>
                                <br/>
                                <strong>{{$photography->description}}</strong><br/>
                                <em><span rel="tooltip" title="{{ $photography->photographed_at->locale('es_MX')->isoFormat('LL') }}">{{$photography->photographed_at->diffForHumans()}}</span></em>
                            </div>
                        @endforeach
                    </div>
                    <a class="carousel-control-prev" href="#carousel{{$module}}" role="button" data-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="sr-only">Anterior</span>
                    </a>
                    <a class="carousel-control-next" href="#carousel{{$module}}" role="button" data-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="sr-only">Siguiente</span>
                    </a>
                </div>
                @if ($aux == 0)
                    <div class="alert alert-warning mt-3">No se han asociado imagenes</div>
                @endif
            @else
                <div class="alert alert-warning mt-3">No se han asociado imagenes</div>
            @endif
        </div>
    </div>
</div>
