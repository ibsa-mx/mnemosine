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
<div class="input-group mb-3 @error('origin_number') has-error @enderror">
    <div class="input-group-prepend">
        <span class="input-group-text">No. procedencia</span>
    </div>
    {!! Form::text('origin_number', null, ['class' => $errors->has('origin_number') ? 'form-control is-invalid' : 'form-control', 'placeholder' => '', 'required']) !!}
    @error('origin_number')
        <div class="invalid-feedback">
            {{ $errors->first('origin_number') }}
        </div>
    @enderror
</div>
<div class="input-group mb-3 @error('inventory_number') has-error @enderror">
    <div class="input-group-prepend">
        <span class="input-group-text">No. inventario</span>
    </div>
    {!! Form::text('inventory_number', null, ['class' => $errors->has('inventory_number') ? 'form-control is-invalid' : 'form-control', 'placeholder' => '', 'required']) !!}
    @error('inventory_number')
        <div class="invalid-feedback">
            {{ $errors->first('inventory_number') }}
        </div>
    @enderror
</div>
<div class="input-group mb-3 @error('catalog_number') has-error @enderror">
    <div class="input-group-prepend">
        <span class="input-group-text">No. catálogo</span>
    </div>
    {!! Form::text('catalog_number', null, ['class' => $errors->has('catalog_number') ? 'form-control is-invalid' : 'form-control', 'placeholder' => '', 'required']) !!}
    @error('catalog_number')
        <div class="invalid-feedback">
            {{ $errors->first('catalog_number') }}
        </div>
    @enderror
</div>
<div class="input-group mb-3 @error('description_origin') has-error @enderror">
    <div class="input-group-prepend">
        <span class="input-group-text">Descripción</span>
    </div>
    {!! Form::textarea('description_origin', null, ['class' => $errors->has('description_origin') ? 'form-control is-invalid' : 'form-control', 'placeholder' => '', 'rows' => '3', 'required']) !!}
    @error('description_origin')
        <div class="invalid-feedback">
            {{ $errors->first('description_origin') }}
        </div>
    @enderror
</div>
<div class="input-group mb-3 @error('gender_id') has-error @enderror">
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
<div class="input-group mb-3 @error('subgender_id') has-error @enderror">
    <div class="input-group-prepend">
        <span class="input-group-text">Subgénero</span>
    </div>
    <select class="form-control select2 @error('subgender_id') is-invalid @enderror" name="subgender_id" id="subgenero">
    <option selected="selected" value="">Seleccione una opción</option>
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
<div class="input-group mb-3 @error('type_object_id') has-error @enderror">
    <div class="input-group-prepend">
        <span class="input-group-text">Tipo de objeto</span>
    </div>
    <select class="form-control select2 @error('type_object_id') is-invalid @enderror" name="type_object_id" id="tipo_objeto">
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
<div class="input-group mb-3 @error('tags') has-error @enderror">
    <div class="input-group-prepend">
        <span class="input-group-text">Mueble</span>
    </div>
    <select class="form-control select2-tags @error('tags') is-invalid @enderror" name="tags[]" id="mueble" multiple="multiple">
        @isset($piece->tags)
            @foreach (explode(",", $piece->tags) as $tag)
                <option selected="selected">{{$tag}}</option>
            @endforeach
        @endisset
    </select>
    @error('tags')
        <div class="invalid-feedback">
            {{ $errors->first('tags') }}
        </div>
    @enderror
</div>
@if (!isset($piece))
    <div class="input-group mb-3 @error('location_id') has-error @enderror">
        <div class="input-group-prepend">
            <span class="input-group-text">Ubicación</span>
        </div>
        <select class="form-control select2 @error('location_id') is-invalid @enderror" name="location_id" id="ubicacion" required="required">
            <option selected="selected" disabled="disabled">Seleccione una opción</option>
        @foreach($locations as $location)
            <option value="{{$location->id}}"{{(isset($piece) && $location->id == $piece->location_id) ? ' selected' : '' }}>
                {{$location->name}}
            </option>
        @endforeach
        </select>
        @error('location_id')
            <div class="invalid-feedback">
                {{ $errors->first('location_id') }}
            </div>
        @enderror
    </div>

    <div class="input-group mb-3 @error('admitted_at') has-error @enderror">
        <div class="input-group-prepend">
            <span class="input-group-text">Fecha de ingreso</span>
        </div>
        {!! Form::text('admitted_at', isset($piece->admitted_at) ? $piece->admitted_at->format('Y-m-d') : today()->format('Y-m-d'), ['class' => $errors->has('admitted_at') ? 'form-control is-invalid' : 'form-control', 'placeholder' => '', 'autocomplete' => 'off']) !!}
        @error('admitted_at')
            <div class="invalid-feedback">
                {{ $errors->first('admitted_at') }}
            </div>
        @enderror
    </div>
@else
    <!-- Mostrar ubicacion para consulta -->
    <div class="mb-3">
        <span class="label">Ubicación:</span>
            <strong>
            @isset($piece->location)
                {{$piece->location->name}}
            @else
                <span class='text-danger'>En préstamo</span>
            @endisset
        </strong>
    </div>
@endif

@if(!isset($piece) || auth()->user()->can('eliminar_inventario'))
    <div class="input-group mb-3 @error('appraisal') has-error @enderror">
        <div class="input-group-prepend">
            <span class="input-group-text">Avalúo $</span>
        </div>
        {!! Form::hidden('current_appraisal', isset($piece->appraisal) ? $piece->appraisal : null) !!}
        {!! Form::number('appraisal', null, ['class' => $errors->has('appraisal') ? 'form-control is-invalid' : 'form-control', 'placeholder' => 'Ingrese el monto en dólares, sin incluir simbolo de pesos o separador de miles', 'autocomplete' => 'off', 'step' => '0.01', 'min' => '0']) !!}
        <div class="input-group-append">
            <span class="input-group-text">USD</span>
        </div>
        @error('appraisal')
            <div class="invalid-feedback">
                {{ $errors->first('appraisal') }}
            </div>
        @enderror
    </div>
    <div class="modal fade" id="confirmAppraisal" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog modal-primary" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Avalúo modificado</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            ¿En verdad desea modificar el avalúo?
            <div class="text-center mt-3">
                <label class="switch switch-label switch-pill switch-outline-success-alt align-bottom">
                    {!! Form::checkbox("chkConfirmAppraisal", "", false, ['class' => 'switch-input switch-group']) !!}
                    <span class="switch-slider" data-checked="Sí" data-unchecked="No"></span>
                </label>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
            <button type="button" class="btn btn-primary" id="btnConfirmAppraisal">Continuar</button>
          </div>
        </div>
      </div>
    </div>
@endif

@php
    $checkBase = '';
    $checkFrame = '';
    $measureType = 'base';
    if(!isset($piece)){
        $checkBase = ' checked';
    } else{
        if($piece->base_or_frame == 'base'){
            $checkBase = ' checked';
        } elseif($piece->base_or_frame == 'frame'){
            $checkFrame = ' checked';
            $measureType = 'marco';
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
<div class="row border border-primary mb-3">
    <div class="col-12 bg-primary mb-3">
        <strong>Medidas de la pieza <em>sin <span class="label-measure-type">{{$measureType}}</span></em> (cm)</strong>
    </div>
    <div class="col">
        <div class="input-group mb-3 @error('height') has-error @enderror">
            <div class="input-group-prepend">
                <span class="input-group-text">Alto</span>
            </div>
            {!! Form::number('height', null, ['class' => $errors->has('height') ? 'form-control is-invalid' : 'form-control', 'placeholder' => '', 'step' => '0.01', 'min' => '0']) !!}
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
            {!! Form::number('width', null, ['class' => $errors->has('width') ? 'form-control is-invalid' : 'form-control', 'placeholder' => '', 'step' => '0.01', 'min' => '0']) !!}
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
            {!! Form::number('depth', null, ['class' => $errors->has('depth') ? 'form-control is-invalid' : 'form-control', 'placeholder' => '', 'step' => '0.01', 'min' => '0']) !!}
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
            {!! Form::number('diameter', null, ['class' => $errors->has('diameter') ? 'form-control is-invalid' : 'form-control', 'placeholder' => '', 'step' => '0.01', 'min' => '0']) !!}
            @error('diameter')
                <div class="invalid-feedback">
                    {{ $errors->first('diameter') }}
                </div>
            @enderror
        </div>
    </div>
</div>
<div class="row border border-primary mb-3">
    <div class="col-12 bg-primary mb-3">
        <strong>Medidas de la pieza <em>con <span class="label-measure-type">{{$measureType}}</span></em> (cm)</strong>
    </div>
    <div class="col">
        <div class="input-group mb-3 @error('height_with_base') has-error @enderror">
            <div class="input-group-prepend">
                <span class="input-group-text">Alto</span>
            </div>
            {!! Form::number('height_with_base', null, ['class' => $errors->has('height_with_base') ? 'form-control is-invalid' : 'form-control', 'placeholder' => '', 'step' => '0.01', 'min' => '0']) !!}
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
            {!! Form::number('width_with_base', null, ['class' => $errors->has('width_with_base') ? 'form-control is-invalid' : 'form-control', 'placeholder' => '', 'step' => '0.01', 'min' => '0']) !!}
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
            {!! Form::number('depth_with_base', null, ['class' => $errors->has('depth_with_base') ? 'form-control is-invalid' : 'form-control', 'placeholder' => '', 'step' => '0.01', 'min' => '0']) !!}
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
            {!! Form::number('diameter_with_base', null, ['class' => $errors->has('diameter_with_base') ? 'form-control is-invalid' : 'form-control', 'placeholder' => '', 'step' => '0.01', 'min' => '0']) !!}
            @error('diameter_with_base')
                <div class="invalid-feedback">
                    {{ $errors->first('diameter_with_base') }}
                </div>
            @enderror
        </div>
    </div>
</div>
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

@push('after_jquery')
<script type="text/javascript">
    var filePhotosInitOptions = {
		language: "es",
		theme: "fas",
        maxFileCount: 1,
		allowedFileExtensions: ["jpg", "jpeg", "gif", "png", "tif", "tiff", "bmp", "svg"],
		showUpload: false,
		maxFilePreviewSize: 1024,
        maxFileSize: {!! config('fileuploads.inventory.photographs.maximum_size') !!}
	};
@isset($piece)
    // info para las fotos
    var urlStoragePhotographs = '{!! Storage::url('') !!}{!! config('fileuploads.inventory.photographs.originals') !!}/';
    var photosBD = JSON.parse('{!! json_encode($photographs->groupBy('id')) !!}');
@endisset
</script>
@endpush

@push('after_all_scripts')
    <script src="{{ asset('admin/js/inventario.js?v=20191207') }}"> </script>
    <script src="{{ asset('admin/js/upload-photos.js') }}"> </script>
@endpush
