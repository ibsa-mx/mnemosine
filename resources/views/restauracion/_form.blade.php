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
<div class="card card-accent-info">
    <div class="card-header h6" role="tab">
        <a href="#" data-toggle="collapse" data-target="#collapseInventario">
            Información de inventario
        </a>
        <div class="card-header-actions">
            <a class="card-header-action btn-minimize" href="#" data-toggle="collapse" data-target="#collapseInventario" aria-expanded="true" rel="tooltip" title="Mostrar u ocultar">
                <i class="icon-arrow-up"></i>
            </a>
        </div>
    </div>
    <div id="collapseInventario" class="collapse">
        <div class="card-body py-2">
            <div class="row mb-2">
                <div class="col-md">
                    <span class="label">No. procedencia:</span>
                    {{$piece->origin_number}}
                </div>
                <div class="col-md">
                    <span class="label">No. inventario:</span>
                    {{$piece->inventory_number}}
                </div>
                <div class="col-md">
                    <span class="label">No. catálogo:</span>
                    {{$piece->catalog_number}}
                </div>
            </div>
            <div class="row mb-2">
                <div class="col">
                    <span class="label">Mueble:</span>
                    @isset($piece->tags)
                        @foreach (explode(",", $piece->tags) as $tag)
                            <span class="badge badge-primary">{{$tag}}</span>
                        @endforeach
                    @endisset
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-md">
                    <span class="label">Género:</span>
                    {{!is_null($gender) ? $gender->title : ''}}
                </div>
                <div class="col-md">
                    <span class="label">Subgénero:</span>
                    {{!is_null($subgender) ? $subgender->title : ''}}
                </div>
                <div class="col-md">
                    <span class="label">Tipo de objeto:</span>
                    {{!is_null($objectType) ? $objectType->title : ''}}
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
            <div class="row">
                <div class="col-6 text-center text-muted">
                    <span class="label"><em>Fecha de creación:</em></span>
                    <span rel="tooltip" title="{{ $piece->created_at->locale('es_MX')->isoFormat('LLLL') }}">{{$piece->created_at->diffForHumans()}}</span>
                </div>
                <div class="col-6 text-center text-muted">
                    <span class="label"><em>Fecha de modificación:</em></span>
                    <span rel="tooltip" title="{{ $piece->updated_at->locale('es_MX')->isoFormat('LLLL') }}">{{$piece->updated_at->diffForHumans()}}</span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container">
    {!! Form::hidden('piece_id', $piece->id) !!}
        <div class="row border border-primary mb-3">
            <div class="col-12 bg-primary">
                <strong>Campos de inventario</strong>
            </div>
            <div class="col-12 pt-3">
                @php
                    $checkBase = '';
                    $checkFrame = '';
                    $measureType = 'base';
                    if(!isset($piece) && !isset($restoration)){
                        $checkBase = ' checked';
                    } else{
                        if(!isset($restoration)){
                            // create
                            if($piece->base_or_frame == 'base'){
                                $checkBase = ' checked';
                            } elseif($piece->base_or_frame == 'frame'){
                                $checkFrame = ' checked';
                                $measureType = 'marco';
                            }
                        } else{
                            // edit
                            if($restoration->base_or_frame == 'base'){
                                $checkBase = ' checked';
                            } elseif($restoration->base_or_frame == 'frame'){
                                $checkFrame = ' checked';
                                $measureType = 'marco';
                            }
                        }

                    }
                @endphp
                <div class="input-group mb-3 text-center">
                    <div class="input-group-prepend">
                        <span class="input-group-text">Medidas de la pieza para</span>
                    </div>
                    <div class="border border-secondary rounded-right flex w-auto">
                        <div class="custom-control custom-radio custom-control-inline mt-1 ml-3">
                            <input type="radio" id="measureBase" name="base_or_frame" class="custom-control-input" value="base"{{$checkBase}}>
                            <label class="custom-control-label" for="measureBase">Base</label>
                        </div>
                        <div class="custom-control custom-radio custom-control-inline mt-1">
                            <input type="radio" id="measureFrame" name="base_or_frame" class="custom-control-input" value="frame"{{$checkFrame}}>
                            <label class="custom-control-label" for="measureFrame">Marco</label>
                        </div>
                    </div>
                </div>
                <div class="row border border-primary mb-3 mx-auto">
                    <div class="col-12 bg-primary mb-3">
                        <strong>Medidas de la pieza <em>sin <span class="label-measure-type">{{$measureType}}</span></em> (cm)</strong>
                    </div>
                    <div class="col">
                        <div class="input-group mb-3 @error('height') has-error @enderror">
                            <div class="input-group-prepend">
                                <span class="input-group-text">Alto</span>
                            </div>
                            {!! Form::number('height', isset($restoration) ? $restoration->height : $piece->height, ['class' => $errors->has('height') ? 'form-control is-invalid' : 'form-control', 'placeholder' => '', 'step' => '0.01', 'min' => '0']) !!}
                            @error('height')
                                <div class="invalid-feedback">
                                    {{ $errors->first('height') }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="col">
                        <div class="input-group mb-3 @error('width') has-error @enderror">
                            <div class="input-group-prepend">
                                <span class="input-group-text">Ancho</span>
                            </div>
                            {!! Form::number('width', isset($restoration) ? $restoration->width : $piece->width, ['class' => $errors->has('width') ? 'form-control is-invalid' : 'form-control', 'placeholder' => '', 'step' => '0.01', 'min' => '0']) !!}
                            @error('width')
                                <div class="invalid-feedback">
                                    {{ $errors->first('width') }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="w-100"></div>
                    <div class="col">
                        <div class="input-group mb-3 @error('depth') has-error @enderror">
                            <div class="input-group-prepend">
                                <span class="input-group-text">Profundo</span>
                            </div>
                            {!! Form::number('depth', isset($restoration) ? $restoration->depth : $piece->depth, ['class' => $errors->has('depth') ? 'form-control is-invalid' : 'form-control', 'placeholder' => '', 'step' => '0.01', 'min' => '0']) !!}
                            @error('depth')
                                <div class="invalid-feedback">
                                    {{ $errors->first('depth') }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="col">
                        <div class="input-group mb-3 @error('diameter') has-error @enderror">
                            <div class="input-group-prepend">
                                <span class="input-group-text">Diámetro</span>
                            </div>
                            {!! Form::number('diameter', isset($restoration) ? $restoration->diameter : $piece->diameter, ['class' => $errors->has('diameter') ? 'form-control is-invalid' : 'form-control', 'placeholder' => '', 'step' => '0.01', 'min' => '0']) !!}
                            @error('diameter')
                                <div class="invalid-feedback">
                                    {{ $errors->first('diameter') }}
                                </div>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="row border border-primary mb-3 mx-auto">
                    <div class="col-12 bg-primary mb-3">
                        <strong>Medidas de la pieza <em>con <span class="label-measure-type">{{$measureType}}</span></em> (cm)</strong>
                    </div>
                    <div class="col">
                        <div class="input-group mb-3 @error('height_with_base') has-error @enderror">
                            <div class="input-group-prepend">
                                <span class="input-group-text">Alto</span>
                            </div>
                            {!! Form::number('height_with_base', isset($restoration) ? $restoration->height_with_base : $piece->height_with_base, ['class' => $errors->has('height_with_base') ? 'form-control is-invalid' : 'form-control', 'placeholder' => '', 'step' => '0.01', 'min' => '0']) !!}
                            @error('height_with_base')
                                <div class="invalid-feedback">
                                    {{ $errors->first('height_with_base') }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="col">
                        <div class="input-group mb-3 @error('width_with_base') has-error @enderror">
                            <div class="input-group-prepend">
                                <span class="input-group-text">Ancho</span>
                            </div>
                            {!! Form::number('width_with_base', isset($restoration) ? $restoration->width_with_base : $piece->width_with_base, ['class' => $errors->has('width_with_base') ? 'form-control is-invalid' : 'form-control', 'placeholder' => '', 'step' => '0.01', 'min' => '0']) !!}
                            @error('width_with_base')
                                <div class="invalid-feedback">
                                    {{ $errors->first('width_with_base') }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="w-100"></div>
                    <div class="col">
                        <div class="input-group mb-3 @error('depth_with_base') has-error @enderror">
                            <div class="input-group-prepend">
                                <span class="input-group-text">Profundo</span>
                            </div>
                            {!! Form::number('depth_with_base', isset($restoration) ? $restoration->depth_with_base : $piece->depth_with_base, ['class' => $errors->has('depth_with_base') ? 'form-control is-invalid' : 'form-control', 'placeholder' => '', 'step' => '0.01', 'min' => '0']) !!}
                            @error('depth_with_base')
                                <div class="invalid-feedback">
                                    {{ $errors->first('depth_with_base') }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="col">
                        <div class="input-group mb-3 @error('diameter_with_base') has-error @enderror">
                            <div class="input-group-prepend">
                                <span class="input-group-text">Diámetro</span>
                            </div>
                            {!! Form::number('diameter_with_base', isset($restoration) ? $restoration->diameter_with_base : $piece->diameter_with_base, ['class' => $errors->has('diameter_with_base') ? 'form-control is-invalid' : 'form-control', 'placeholder' => '', 'step' => '0.01', 'min' => '0']) !!}
                            @error('diameter_with_base')
                                <div class="invalid-feedback">
                                    {{ $errors->first('diameter_with_base') }}
                                </div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>



        <!-- DATOS DE RESTAURACION -->
    <div class="input-group mb-3 @error('preliminary_examination') has-error @enderror">
        <div class="input-group-prepend">
            <span class="input-group-text">Exámen preliminar</span>
        </div>
        {!! Form::textarea('preliminary_examination', null, ['class' => $errors->has('preliminary_examination') ? 'form-control is-invalid' : 'form-control', 'placeholder' => '', 'rows' => '5']) !!}
        @error('preliminary_examination')
            <div class="invalid-feedback">
                {{ $errors->first('preliminary_examination') }}
            </div>
        @enderror
    </div>
    <div class="input-group mb-3 @error('laboratory_analysis') has-error @enderror">
        <div class="input-group-prepend">
            <span class="input-group-text">Análisis de laboratorio</span>
        </div>
        {!! Form::textarea('laboratory_analysis', null, ['class' => $errors->has('laboratory_analysis') ? 'form-control is-invalid' : 'form-control', 'placeholder' => '', 'rows' => '5']) !!}
        @error('laboratory_analysis')
            <div class="invalid-feedback">
                {{ $errors->first('laboratory_analysis') }}
            </div>
        @enderror
    </div>
    <div class="input-group mb-3 @error('proposal_of_treatment') has-error @enderror">
        <div class="input-group-prepend">
            <span class="input-group-text">Propuesta de tratamiento</span>
        </div>
        {!! Form::textarea('proposal_of_treatment', null, ['class' => $errors->has('proposal_of_treatment') ? 'form-control is-invalid' : 'form-control', 'placeholder' => '', 'rows' => '5']) !!}
        @error('proposal_of_treatment')
            <div class="invalid-feedback">
                {{ $errors->first('proposal_of_treatment') }}
            </div>
        @enderror
    </div>
    <div class="input-group mb-3 @error('treatment_description') has-error @enderror">
        <div class="input-group-prepend">
            <span class="input-group-text">Descripción de tratamiento</span>
        </div>
        {!! Form::textarea('treatment_description', null, ['class' => $errors->has('treatment_description') ? 'form-control is-invalid' : 'form-control', 'placeholder' => '', 'rows' => '5']) !!}
        @error('treatment_description')
            <div class="invalid-feedback">
                {{ $errors->first('treatment_description') }}
            </div>
        @enderror
    </div>
    <div class="input-group mb-3 @error('results') has-error @enderror">
        <div class="input-group-prepend">
            <span class="input-group-text">Resultado y recomendaciones</span>
        </div>
        {!! Form::textarea('results', null, ['class' => $errors->has('results') ? 'form-control is-invalid' : 'form-control', 'placeholder' => '', 'rows' => '5']) !!}
        @error('results')
            <div class="invalid-feedback">
                {{ $errors->first('results') }}
            </div>
        @enderror
    </div>
    <div class="input-group mb-3 @error('observations') has-error @enderror">
        <div class="input-group-prepend">
            <span class="input-group-text">Observaciones</span>
        </div>
        {!! Form::textarea('observations', null, ['class' => $errors->has('observations') ? 'form-control is-invalid' : 'form-control', 'placeholder' => '', 'rows' => '5']) !!}
        @error('observations')
            <div class="invalid-feedback">
                {{ $errors->first('observations') }}
            </div>
        @enderror
    </div>


    <div class="input-group mb-3 @error('treatment_date') has-error @enderror">
        <div class="input-group-prepend">
            <span class="input-group-text">Fecha de tratamiento</span>
        </div>
        {!! Form::text('treatment_date', null, ['class' => $errors->has('treatment_date') ? 'form-control is-invalid' : 'form-control', 'placeholder' => '', 'required']) !!}
        @error('treatment_date')
            <div class="invalid-feedback">
                {{ $errors->first('treatment_date') }}
            </div>
        @enderror
    </div>
    <div class="input-group mb-3 @error('responsible_restorer') has-error @enderror">
        <div class="input-group-prepend">
            <span class="input-group-text">Restaurador responsable</span>
        </div>
        <select class="form-control select2 @error('responsible_restorer') is-invalid @enderror" name="responsible_restorer" required="required">
            <option selected="selected" disabled="disabled">Seleccione una opción</option>
        @foreach($responsibleRestorers as $responsibleRestorer)
            <option value="{{$responsibleRestorer->id}}"{{(isset($restoration) && $responsibleRestorer->id == $restoration->responsible_restorer) ? ' selected' : '' }}>
                {{$responsibleRestorer->title}}
            </option>
        @endforeach
        </select>
        @error('responsible_restorer')
            <div class="invalid-feedback">
                {{ $errors->first('responsible_restorer') }}
            </div>
        @enderror
    </div>




    <!-- Documentos de restauracion -->
        <div class="row border border-primary mb-3">
            <div class="col-12 bg-primary mb-1">
                <strong>Documentos asociados a esta pieza</strong>
                {!! Form::hidden('document_ids_bd', isset($documents) ? implode(",", $documents->pluck('id')->all()) : null) !!}
                {!! Form::hidden('document_ids_bd_deleted', null) !!}
            </div>
            <div class="col-12">
                @include('shared._upload-documents')
            </div>
        </div>

    <!-- Imagenes de restauracion -->
        <div class="row border border-primary mb-3">
            <div class="col-12 bg-primary mb-1">
                <strong>Imágenes asociadas a esta pieza</strong>
                {!! Form::hidden('photo_ids_bd', isset($photographs) ? implode(",", $photographs->pluck('id')->all()) : null) !!}
                {!! Form::hidden('photo_ids_bd_deleted', null) !!}
            </div>
            <div class="col-12">
                @include('shared._upload-photos')
            </div>
        </div>
</div>



@push('after_all_styles')
    <link href="{{asset('admin/css/show.css')}}" rel="stylesheet">
@endpush

@push('after_jquery')
<script type="text/javascript">
    var filePhotosInitOptions = {
		language: "es",
        maxFileCount: 1,
		theme: "fas",
		allowedFileExtensions: ["jpg", "jpeg", "gif", "png", "tif", "tiff", "bmp", "svg"],
		showUpload: false,
		maxFilePreviewSize: 4096,
        maxFileSize: {!! config('fileuploads.restoration.photographs.maximum_size') !!}
	};
    var fileDocumentsInitOptions = {
		language: "es",
        maxFileCount: 1,
        theme: "explorer-fas",
        allowedFileExtensions: ['doc', 'docx', 'xml', 'xlsx', 'xls', 'ppt', 'pptx', 'html', 'htm', 'txt', 'pdf'],
		showUpload: false,
		maxFilePreviewSize: 1024,
        maxFileSize: {!! config('fileuploads.restoration.documents.maximum_size') !!}
	};
</script>
@endpush

@push('after_all_scripts')
    <script src="{{ asset('admin/js/restauracion.js') }}"> </script>
    <script src="{{ asset('admin/js/upload-photos.js') }}"> </script>
    <script src="{{ asset('admin/js/upload-documents.js') }}"> </script>
@endpush
