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
@php
    $checkExternal = false;
    $checkInternal = false;
    if(!isset($movimiento)){
        $checkExternal = true;
    } else{
        if(!is_null($movimiento->movement_type) && $movimiento->movement_type == 'external'){
            $checkExternal = true;
        } else{
            $checkInternal = true;
        }
    }
@endphp
<input type="hidden" id="movement_type_init" value="{{isset($movimiento->movement_type) ? $movimiento->movement_type : ''}}"/>
<input type="hidden" id="movement_itinerant" value="{{isset($movimiento->itinerant) ? (integer)$movimiento->itinerant : '0'}}"/>
<div class="row">
    <div class="col-sm-12 col-md-7">
        <div class="input-group mb-3 text-center">
            <div class="input-group-prepend">
                <span class="input-group-text">Tipo de movimiento</span>
            </div>
            <div class="border border-secondary rounded-right flex w-auto">
                <div class="custom-control custom-radio custom-control-inline my-1 ml-3">
                    {!! Form::radio('movement_type', 'external', $checkExternal, ['id' => 'external', 'class' => 'custom-control-input']) !!}
                    <label class="custom-control-label" for="external">Externo</label>
                </div>
                <div class="custom-control custom-radio custom-control-inline my-1">
                    {!! Form::radio('movement_type', 'internal', $checkInternal, ['id' => 'internal', 'class' => 'custom-control-input']) !!}
                    <label class="custom-control-label" for="internal">Interno</label>
                </div>
            </div>
        </div>
    </div>
    {{-- Itinerante --}}
    <div class="col-sm-12 col-md-5">
        <div class="input-group form-group" id="div_itinerant">
        	<div class="input-group-prepend">
        		<span class="input-group-text">Itinerante</span>
        	</div>
        	<div class="border border-secondary rounded-right flex w-auto">
                <label class="switch switch-label switch-pill switch-outline-success-alt align-bottom mt-1 mx-3 mb-0">
                    {!! Form::checkbox("itinerant", "1", isset($movimiento->itinerant) ? (bool)$movimiento->itinerant : false, ['class' => 'switch-input switch-group', 'id' => 'itinerante']) !!}
                    <span class="switch-slider" data-checked="Sí" data-unchecked="No"></span>
                </label>
        	</div>
        </div>
    </div>
</div>
@php
    $internalInstitution = $instituciones->where('name', config('app.institution'))->toArray();
@endphp
<div id="linst">
    <div class="input-group-prepend mb-3">
        <span class="input-group-text">Institución</span>
        <label class="m-1 ml-2">{{config('app.institution')}}</label>
        <input type="hidden" name="internal_institution" id="internal_institution" value="0"/>
        <input type="hidden" name="internal_institution_id" id="internal_institution_id" value="{{$internalInstitution[0]['id']}}"/>
    </div>
</div>
<div id="inst" class="input-group mb-3 @error('institution_ids') has-error @enderror">
	<div class="input-group-prepend">
		<span class="input-group-text">Institución</span>
	</div>
    <select class="form-control form-group select2-multiple @error('institution_ids') is-invalid @enderror" name="institution_ids[]" id="institucion" multiple="multiple">
    	@if($instituciones != null)
    		@foreach($instituciones as $institucion)
                @continue($institucion->id == 1)
    			<option value="{{$institucion->id}}" {{(isset($movimiento) && in_array($institucion->id, explode(',', $movimiento->institution_ids)))? 'selected':''}}>{{$institucion->name}}</option>
    		@endforeach
    	@endif
    </select>
    @error('institution_ids')
        <div class="invalid-feedback">
            {{ $errors->first('institution_ids') }}
        </div>
    @enderror
</div>
<div class="row border border-primary mb-3">
    <div class="col-12 bg-primary mb-3">
        <strong>Contactos resposables</strong>
    </div>
    <div class="col-12">
        <div class="input-group mb-3 @error('contact_ids') has-error @enderror">
        	<div class="input-group-prepend">
        		<span class="input-group-text">Movimiento</span>
        	</div>
            <select class="form-control form-group select2-multiple @error('contact_ids') is-invalid @enderror" name="contact_ids[]" id="contacto" multiple="multiple" required="required">
            	@if($contactos != null)
            		@foreach($contactos as $contacto)
            			<option value="{{$contacto->id}}" {{(isset($movimiento) && in_array($contacto->id, explode(',', $movimiento->contact_ids))) ? 'selected':''}}>{{$contacto->name}} {{$contacto->last_name}}</option>
            		@endforeach
            	@endif
            </select>
            @error('contact_ids')
                <div class="invalid-feedback">
                    {{ $errors->first('contact_ids') }}
                </div>
            @enderror
        </div>
    </div>
    <div class="col-12">
        <div id="div_contacto_resguardo" class="input-group mb-3 @error('guard_contact_ids') has-error @enderror">
        	<div class="input-group-prepend">
        		<span class="input-group-text">Resguardo</span>
        	</div>
            <select class="form-control form-group select2-multiple @error('guard_contact_ids') is-invalid @enderror" name="guard_contact_ids[]" id="contacto_resguardo" multiple="multiple">
            	@if($contactos != null)
            		@foreach($contactos as $contacto)
            			<option value="{{$contacto->id}}" {{(isset($movimiento) && in_array($contacto->id, explode(',', $movimiento->guard_contact_ids))) ? 'selected':''}}>{{$contacto->name}} {{$contacto->last_name}}</option>
            		@endforeach
            	@endif
            </select>
            @error('guard_contact_ids')
                <div class="invalid-feedback">
                    {{ $errors->first('guard_contact_ids') }}
                </div>
            @enderror
        </div>
    </div>
</div>

<div id="expo" class="input-group mb-3 @error('exhibition_id') has-error @enderror">
	<div class="input-group-prepend">
		<span class="input-group-text">Ubicación / Exposición</span>
	</div>
    <select class="form-control form-group select2 @error('exhibition_id') is-invalid @enderror" name="exhibition_id" id="exposicion" required="required">
    	@if($expos != null)
    		@foreach($expos as $c)
    			<option value="{{$c->id}}" {{(isset($movimiento) && $movimiento->exhibition_id == $c->id)? 'selected': ''}}> {{$c->name}}</option>
    		@endforeach
    	@endif
    </select>
    @error('exhibition_id')
        <div class="invalid-feedback">
            {{ $errors->first('exhibition_id') }}
        </div>
    @enderror
</div>
<div id="sede_div" class="input-group mb-3 @error('venues') has-error @enderror">
	<div class="input-group-prepend">
		<span class="input-group-text">Sede</span>
	</div>
    <select class="form-control form-group select2  @error('venues') is-invalid @enderror" name="venues[]" id="sede"{{(isset($movimiento->itinerant) && $movimiento->itinerant) ? ' multiple="multiple"' : ''}}>
    	@if($sedes != null)
    		@foreach($sedes as $c)
    			<option value="{{$c->id}}"
                    @if(isset($movimiento) && $movimiento->venues != null)
                       @if(is_array($var) || is_object($var))
                            @foreach($var as $v)
                            {{($v == $c->id)? 'selected': ''}}
                            @endforeach
                        @endif
                    @endif> {{$c->name}}</option>
    		@endforeach
    	@endif
    </select>
    @error('venues')
        <div class="invalid-feedback">
            {{ $errors->first('venues') }}
        </div>
    @enderror
</div>
<div class="input-group mb-3 @error('departure_date') has-error @enderror">
	<div class="input-group-prepend">
		<span class="input-group-text">Fecha de salida</span>
	</div>
    {!! Form::text('departure_date', isset($movimiento->departure_date) ? $movimiento->departure_date->format('Y-m-d') : today()->format('Y-m-d'), ['class' => 'form-control daterange-single']) !!}
    @error('departure_date')
        <div class="invalid-feedback">
            {{ $errors->first('departure_date') }}
        </div>
    @enderror
</div>
<div class="input-group mb-3 @error('start_exposure') has-error @enderror">
	<div class="input-group-prepend">
		<span class="input-group-text">Fecha inicial de exhibición</span>
	</div>
    {!! Form::text('start_exposure', isset($movimiento->start_exposure) ? $movimiento->start_exposure->format('Y-m-d') : today()->format('Y-m-d'), ['class' => 'form-control daterange-single']) !!}
    @error('start_exposure')
        <div class="invalid-feedback">
            {{ $errors->first('start_exposure') }}
        </div>
    @enderror
</div>
<div class="input-group mb-3 @error('end_exposure') has-error @enderror">
	<div class="input-group-prepend">
		<span class="input-group-text">Fecha final de exhibición</span>
	</div>
    {!! Form::text('end_exposure', isset($movimiento->end_exposure) ? $movimiento->end_exposure->format('Y-m-d') : '', ['class' => 'form-control daterange-single']) !!}
    @error('end_exposure')
        <div class="invalid-feedback">
            {{ $errors->first('end_exposure') }}
        </div>
    @enderror
</div>
<div class="input-group mb-3 @error('observations') has-error @enderror">
	<div class="input-group-prepend">
		<span class="input-group-text">Observaciones</span>
	</div>
    {!! Form::textarea('observations', null, ['class' => 'form-control', 'cols' => '3', 'rows' => '3']) !!}
    @error('observations')
        <div class="invalid-feedback">
            {{ $errors->first('observations') }}
        </div>
    @enderror
</div>
<div class="custom-control custom-checkbox mb-2 text-center">
    {!! Form::checkbox('paso2', '1', true, ['class' => 'custom-control-input', 'id' => 'paso2']) !!}
    <label class="custom-control-label text-danger" for="paso2">Ir al paso 2</label>
</div>
<input type="hidden" name="p1" value="1">
