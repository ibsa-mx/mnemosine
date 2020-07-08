@extends('admin.layout')

@section('title', '- Investigación, editar datos')

@section('breadcrumb')
    <li class="breadcrumb-item active">
        <a href="{{route('investigacion.index')}}">Investigación</a>
    </li>
    <li class="breadcrumb-item">
        Editar datos de investigación
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
            <div class="row">
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
            <div class="row my-2">
                <div class="col">
                    <span class="label">Mueble:</span>
                    @isset($piece->tags)
                        @foreach (explode(",", $piece->tags) as $tag)
                            <span class="badge badge-primary">{{$tag}}</span>
                        @endforeach
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
@isset($research)
    {!! Form::model($research, ['method' => 'PUT', 'route' => ['investigacion.update',  $piece->id ], 'files' => true, 'id' => 'frmInvestigacion']) !!}
@else
    {!! Form::open(['route' => 'investigacion.store', 'files' => true, 'id' => 'frmInvestigacion']) !!}
@endisset
    {!! Form::hidden('piece_id', $piece->id) !!}
        <div class="row border border-primary mb-3">
            <div class="col-12 bg-primary">
                <strong>Campos de inventario</strong>
            </div>
            <div class="col-12 pt-3">
                <div class="input-group mb-2 @error('gender_id') has-error @enderror">
                    <div class="input-group-prepend">
                        <span class="input-group-text">Género</span>
                    </div>
                    <select class="form-control select2 @error('gender_id') is-invalid @enderror" name="gender_id" id="genero">
                    @if ($genders != null)
                        <option selected="selected" disabled="disabled">Seleccione una opción</option>
                        @foreach($genders as $gender)
                            <option value="{{$gender->id}}"{{(isset($piece) && $gender->id == $piece->gender_id) ? ' selected' : '' }}>
                                {{$gender->title}}
                            </option>
                        @endforeach
                    @endif
                    </select>
                    @error('gender_id')
                        <div class="invalid-feedback">
                            {{ $errors->first('gender_id') }}
                        </div>
                    @enderror
                </div>
                <div class="input-group mb-2 @error('subgender_id') has-error @enderror">
                    <div class="input-group-prepend">
                        <span class="input-group-text">Subgénero</span>
                    </div>
                    <select class="form-control select2 @error('subgender_id') is-invalid @enderror" name="subgender_id" id="subgenero">
                    @isset($subgender)
                        @foreach($subgender as $sub)
                            <option value="{{$sub->id}}"{{ isset($piece) && $sub->id == $piece->subgender_id ? ' selected' : '' }}>
                                {{$sub->title}}
                            </option>
                        @endforeach
                    @endisset
                    </select>
                    @error('subgender_id')
                        <div class="invalid-feedback">
                            {{ $errors->first('subgender_id') }}
                        </div>
                    @enderror
                </div>
                <div class="input-group mb-2 @error('type_object_id') has-error @enderror">
                    <div class="input-group-prepend">
                        <span class="input-group-text">Tipo de objeto</span>
                    </div>
                    <select class="form-control select2 @error('type_object_id') is-invalid @enderror" name="type_object_id">
                        <option selected="selected" disabled="disabled">Seleccione una opción</option>
                    @foreach($objectTypes as $objectType)
                        <option value="{{$objectType->id}}"{{(isset($piece) && $objectType->id == $piece->type_object_id) ? ' selected' : '' }}>
                            {{$objectType->title}}
                        </option>
                    @endforeach
                    </select>
                    @error('type_object_id')
                        <div class="invalid-feedback">
                            {{ $errors->first('type_object_id') }}
                        </div>
                    @enderror
                </div>
                <div class="input-group mb-3 @error('description_origin') has-error @enderror">
                    <div class="input-group-prepend">
                        <span class="input-group-text">Descripción</span>
                    </div>
                    {!! Form::textarea('description_origin', $piece->description_origin, ['class' => $errors->has('description_origin') ? 'form-control is-invalid' : 'form-control', 'placeholder' => '', 'rows' => '3']) !!}
                    @error('description_origin')
                        <div class="invalid-feedback">
                            {{ $errors->first('description_origin') }}
                        </div>
                    @enderror
                </div>
            </div>
        </div>


        <!-- DATOS DE INVESTIGACION -->
        <div class="input-group mb-3 @error('title') has-error @enderror">
            <div class="input-group-prepend">
                <span class="input-group-text">Título</span>
            </div>
            {!! Form::text('title', null, ['class' => $errors->has('title') ? 'form-control is-invalid' : 'form-control', 'placeholder' => '', 'required']) !!}
            @error('title')
                <div class="invalid-feedback">
                    {{ $errors->first('title') }}
                </div>
            @enderror
        </div>
        <div class="input-group mb-3 @error('author_ids') has-error @enderror">
            <div class="input-group-prepend">
                <span class="input-group-text">Autor(es)</span>
            </div>
            <select class="form-control select2 @error('author_ids') is-invalid @enderror" name="author_ids[]" multiple>
            @foreach($authors as $author)
                <option value="{{$author->id}}"{{(isset($research) && in_array($author->id, $research->author_ids)) ? ' selected' : '' }}>
                    {{$author->title}}
                </option>
            @endforeach
            </select>
            @error('author_ids')
                <div class="invalid-feedback">
                    {{ $errors->first('author_ids') }}
                </div>
            @enderror
        </div>
        <div class="input-group mb-3 @error('set_id') has-error @enderror">
            <div class="input-group-prepend">
                <span class="input-group-text">Conjunto</span>
            </div>
            <select class="form-control select2 @error('set_id') is-invalid @enderror" name="set_id">
                <option selected="selected" disabled="disabled">Seleccione una opción</option>
            @foreach($sets as $set)
                <option value="{{$set->id}}"{{(isset($research) && $set->id == $research->set_id) ? ' selected' : '' }}>
                    {{$set->title}}
                </option>
            @endforeach
            </select>
            @error('set_id')
                <div class="invalid-feedback">
                    {{ $errors->first('set_id') }}
                </div>
            @enderror
        </div>
        <div class="input-group mb-3 @error('technique') has-error @enderror">
            <div class="input-group-prepend">
                <span class="input-group-text">Técnica</span>
            </div>
            {!! Form::textarea('technique', null, ['class' => $errors->has('technique') ? 'form-control is-invalid' : 'form-control', 'placeholder' => '', 'rows' => '5']) !!}
            @error('technique')
                <div class="invalid-feedback">
                    {{ $errors->first('technique') }}
                </div>
            @enderror
        </div>
        <div class="input-group mb-3 @error('materials') has-error @enderror">
            <div class="input-group-prepend">
                <span class="input-group-text">Materiales</span>
            </div>
            {!! Form::textarea('materials', null, ['class' => $errors->has('materials') ? 'form-control is-invalid' : 'form-control', 'placeholder' => '', 'rows' => '5']) !!}
            @error('materials')
                <div class="invalid-feedback">
                    {{ $errors->first('materials') }}
                </div>
            @enderror
        </div>
        <div class="input-group mb-3 @error('place_of_creation_id') has-error @enderror">
            <div class="input-group-prepend">
                <span class="input-group-text">Procedencia</span>
            </div>
            <select class="form-control select2 @error('place_of_creation_id') is-invalid @enderror" name="place_of_creation_id">
                <option selected="selected" disabled="disabled">Seleccione una opción</option>
            @foreach($placeOfCreations as $placeOfCreation)
                <option value="{{$placeOfCreation->id}}"{{(isset($research) && $placeOfCreation->id == $research->place_of_creation_id) ? ' selected' : '' }}>
                    {{$placeOfCreation->title}}
                </option>
            @endforeach
            </select>
            @error('place_of_creation_id')
                <div class="invalid-feedback">
                    {{ $errors->first('place_of_creation_id') }}
                </div>
            @enderror
        </div>
        <div class="input-group mb-3 @error('creation_date') has-error @enderror">
            <div class="input-group-prepend">
                <span class="input-group-text">Fecha de creación</span>
            </div>
            {!! Form::text('creation_date', null, ['class' => $errors->has('creation_date') ? 'form-control is-invalid' : 'form-control', 'placeholder' => '']) !!}
            @error('creation_date')
                <div class="invalid-feedback">
                    {{ $errors->first('creation_date') }}
                </div>
            @enderror
        </div>
        <div class="input-group mb-3 @error('period_id') has-error @enderror">
            <div class="input-group-prepend">
                <span class="input-group-text">Época</span>
            </div>
            <select class="form-control select2 @error('period_id') is-invalid @enderror" name="period_id">
                <option selected="selected" disabled="disabled">Seleccione una opción</option>
            @foreach($periods as $period)
                <option value="{{$period->id}}"{{(isset($research) && $period->id == $research->period_id) ? ' selected' : '' }}>
                    {{$period->title}}
                </option>
            @endforeach
            </select>
            @error('period_id')
                <div class="invalid-feedback">
                    {{ $errors->first('period_id') }}
                </div>
            @enderror
        </div>
        <div class="row border border-primary mb-3">
            <div class="col-12 bg-primary">
                <strong>Proveniencia</strong>
            </div>
            <div class="col-12 mt-3">
                <div class="input-group mb-2 @error('acquisition_form') has-error @enderror">
                    <div class="input-group-prepend">
                        <span class="input-group-text">Forma</span>
                    </div>
                    {!! Form::text('acquisition_form', null, ['class' => $errors->has('acquisition_form') ? 'form-control is-invalid' : 'form-control', 'placeholder' => '']) !!}
                    @error('acquisition_form')
                        <div class="invalid-feedback">
                            {{ $errors->first('acquisition_form') }}
                        </div>
                    @enderror
                </div>
            </div>
            <div class="col-12">
                <div class="input-group mb-2 @error('acquisition_source') has-error @enderror">
                    <div class="input-group-prepend">
                        <span class="input-group-text">Fuente/lugar</span>
                    </div>
                    {!! Form::text('acquisition_source', null, ['class' => $errors->has('acquisition_source') ? 'form-control is-invalid' : 'form-control', 'placeholder' => '']) !!}
                    @error('acquisition_source')
                        <div class="invalid-feedback">
                            {{ $errors->first('acquisition_source') }}
                        </div>
                    @enderror
                </div>
            </div>
            <div class="col-12">
                <div class="input-group mb-3 @error('acquisition_date') has-error @enderror">
                    <div class="input-group-prepend">
                        <span class="input-group-text">Fecha</span>
                    </div>
                    {!! Form::text('acquisition_date', null, ['class' => $errors->has('acquisition_date') ? 'form-control is-invalid' : 'form-control', 'placeholder' => '']) !!}
                    @error('acquisition_date')
                        <div class="invalid-feedback">
                            {{ $errors->first('acquisition_date') }}
                        </div>
                    @enderror
                </div>
            </div>
        </div>
        <div class="row border border-primary mb-3">
            <div class="col-12 bg-primary">
                <strong>Firmas o marcas</strong>
            </div>
            <div class="col-1 pt-3">
                <div class="input-group">
                    <label class="switch switch-label switch-pill switch-outline-success-alt align-bottom">
                        {!! Form::checkbox("firm", "1", isset($research) ? (bool)$research->firm : false, ['class' => 'switch-input switch-group', 'id' => 'firmas_marcas']) !!}
                        <span class="switch-slider" data-checked="Sí" data-unchecked="No"></span>
                    </label>
                </div>
            </div>
            <div class="col-11 pt-3">
                <div class="input-group mb-3 @error('firm_description') has-error @enderror">
                    <div class="input-group-prepend">
                        <span class="input-group-text">Descripción</span>
                    </div>
                    {!! Form::textarea('firm_description', null, ['class' => $errors->has('firm_description') ? 'form-control is-invalid' : 'form-control', 'placeholder' => '', 'rows' => '5']) !!}
                    @error('firm_description')
                        <div class="invalid-feedback">
                            {{ $errors->first('firm_description') }}
                        </div>
                    @enderror
                </div>
            </div>
        </div>

        <div class="input-group mb-3 @error('short_description') has-error @enderror">
            <div class="input-group-prepend">
                <span class="input-group-text">Descripción abreviada</span>
            </div>
            {!! Form::textarea('short_description', null, ['class' => $errors->has('short_description') ? 'form-control is-invalid' : 'form-control', 'placeholder' => '', 'rows' => '5']) !!}
            @error('short_description')
                <div class="invalid-feedback">
                    {{ $errors->first('short_description') }}
                </div>
            @enderror
        </div>
        <div class="input-group mb-3 @error('formal_description') has-error @enderror">
            <div class="input-group-prepend">
                <span class="input-group-text">Descripción formal</span>
            </div>
            {!! Form::textarea('formal_description', null, ['class' => $errors->has('formal_description') ? 'form-control is-invalid' : 'form-control', 'placeholder' => '', 'rows' => '5']) !!}
            @error('formal_description')
                <div class="invalid-feedback">
                    {{ $errors->first('formal_description') }}
                </div>
            @enderror
        </div>
        <div class="input-group mb-3 @error('observation') has-error @enderror">
            <div class="input-group-prepend">
                <span class="input-group-text">Observaciones</span>
            </div>
            {!! Form::textarea('observation', null, ['class' => $errors->has('observation') ? 'form-control is-invalid' : 'form-control', 'placeholder' => '', 'rows' => '5']) !!}
            @error('observation')
                <div class="invalid-feedback">
                    {{ $errors->first('observation') }}
                </div>
            @enderror
        </div>
        <div class="input-group mb-3 @error('publications') has-error @enderror">
            <div class="input-group-prepend">
                <span class="input-group-text">Publicaciones en las<br/>que aparece la obra</span>
            </div>
            {!! Form::textarea('publications', null, ['class' => $errors->has('publications') ? 'form-control is-invalid' : 'form-control', 'placeholder' => '', 'rows' => '5']) !!}
            @error('publications')
                <div class="invalid-feedback">
                    {{ $errors->first('publications') }}
                </div>
            @enderror
        </div>

    <!-- Notas al pie de pagina -->
    <div class="row border border-primary mb-3">
        <div class="col-12 bg-primary mb-1">
            <a href="#" class="text-white" data-toggle="collapse" data-target="#collapseFootnotes">
                <strong>Nota al pie de página</strong>
            </a>
            {!! Form::hidden('footnote_ids_bd_deleted', null) !!}
            <div class="card-header-actions">
                <a class="card-header-action btn-minimize text-white" href="#" data-toggle="collapse" data-target="#collapseFootnotes" aria-expanded="true" rel="tooltip" title="Mostrar u ocultar">
                    <i class="icon-arrow-up"></i>
                </a>
            </div>
        </div>
        <div class="col-12 collapse show" id="collapseFootnotes">
            @include('investigacion._footnote')
        </div>
    </div>

    <!-- Bibliografía -->
    <div class="row border border-primary mb-3">
        <div class="col-12 bg-primary mb-1">
            <a href="#" class="text-white" data-toggle="collapse" data-target="#collapseBibliography">
                <strong>Bibliografía</strong>
            </a>
            {!! Form::hidden('bibliography_ids_bd_deleted', null) !!}
            <div class="card-header-actions">
                <a class="card-header-action btn-minimize text-white" href="#" data-toggle="collapse" data-target="#collapseBibliography" aria-expanded="true" rel="tooltip" title="Mostrar u ocultar">
                    <i class="icon-arrow-up"></i>
                </a>
            </div>
        </div>
        <div class="col-12 collapse show" id="collapseBibliography">
            @include('investigacion._bibliography')
        </div>
    </div>

    <!-- Documentos de investigacion -->
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

    <!-- Imagenes de investigacion -->
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

    <!-- Cedula -->
        <div class="input-group mb-3 @error('card') has-error @enderror">
            <div class="input-group-prepend">
                <span class="input-group-text">Cédula</span>
            </div>
            {!! Form::textarea('card', null, ['class' => $errors->has('card') ? 'form-control is-invalid' : 'form-control', 'placeholder' => '', 'rows' => '5']) !!}
            @error('card')
                <div class="invalid-feedback">
                    {{ $errors->first('card') }}
                </div>
            @enderror
        </div>

        <div class="text-center mb-3">
            <a href="{{ route('investigacion.index') }}" class="btn btn-secondary">Cancelar</a>
            {!! Form::submit('Guardar cambios', ['class' => 'btn btn-primary']) !!}
        </div>
    {!! Form::close() !!}
</div>
@endsection



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
		maxFilePreviewSize: 1024,
        maxFileSize: {!! config('fileuploads.research.photographs.maximum_size') !!}
	};
    var fileDocumentsInitOptions = {
		language: "es",
        maxFileCount: 1,
        theme: "explorer-fas",
        allowedFileExtensions: ['doc', 'docx', 'xml', 'xlsx', 'xls', 'ppt', 'pptx', 'html', 'htm', 'txt', 'pdf'],
		showUpload: false,
		maxFilePreviewSize: 1024,
        maxFileSize: {!! config('fileuploads.research.documents.maximum_size') !!}
	};
@isset($photographs)
    // info para las fotos
    var urlStoragePhotographs = '{!! Storage::url('') !!}{!! config('fileuploads.research.photographs.originals') !!}/';
    var photosBD = JSON.parse('{!! json_encode($photographs->groupBy('id')) !!}');
@endisset
@isset($documents)
    // info para los documentos
    var urlStorageDocuments = '{!! Storage::url('') !!}{!! config('fileuploads.research.documents.originals') !!}/';
    var documentsBD = JSON.parse('{!! json_encode($documents->groupBy('id')) !!}');
@endisset
@isset($bibliographs)
    // info para las bibliografias
    var bibliographysBD = JSON.parse('{!! json_encode($bibliographs->groupBy('id')) !!}');
@endisset
@isset($footnotes)
    // info para las notas al pie
    var footnotesBD = JSON.parse('{!! json_encode($footnotes->groupBy('id')) !!}');
@endisset
</script>
@endpush

@push('after_all_scripts')
    <script src="{{ asset('admin/js/investigacion.js') }}"> </script>
    <script src="{{ asset('admin/js/bibliography.js') }}"> </script>
    <script src="{{ asset('admin/js/footnote.js') }}"> </script>
    <script src="{{ asset('admin/js/upload-photos.js') }}"> </script>
    <script src="{{ asset('admin/js/upload-documents.js') }}"> </script>
@endpush
