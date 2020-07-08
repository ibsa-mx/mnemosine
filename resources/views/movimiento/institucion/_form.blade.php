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
<div class="col-md input-group mb-3 @error('name') has-error @enderror">
	<div class="input-group-prepend">
		<span class="input-group-text">Nombre</span>
	</div>
    {!! Form::text('name', null, ['class' => $errors->has('name') ? 'form-control is-invalid' : 'form-control', 'placeholder' => 'Escribe el nombre de la institución', 'required']) !!}
    @error('name')
        <div class="invalid-feedback">
            {{ $errors->first('name') }}
        </div>
    @enderror
</div>
<div class="col-md input-group mb-3 @error('address') has-error @enderror">
	<div class="input-group-prepend">
		<span class="input-group-text">Dirección</span>
	</div>
	{!! Form::text('address',  null, ['class' => $errors->has('address') ? 'form-control is.invalid': 'form-control', 'placeholder' => 'Calle/Avenida, N°exterior - N°interior', 'required']) !!}
	@error('address')
		<div class="invalid-feedback">
			{{ $errors->first('address')}}
		</div>
	@enderror
</div>
<div class="input-group">
	<div class="col-md input-group mb-3 @error('country_id') has-error @enderror">
		<div class="input-group-prepend">
			<span class="input-group-text">País</span>
		</div>
		<select class="form-control select2 @error('country_id') is-invalid @enderror" name="country_id" id="country" required="required">
			@if($paises != null)
				<option selected="selected" disabled="disabled">Seleccione una opción</option>
				@foreach($paises as $pais)
				<option value="{{$pais->id}}" {{(isset($institucion) && $institucion->country_id == $pais->id) ? 'selected': ''}}> {{$pais->name}}</option>
				@endforeach
			@endif
		</select>
		@error('country_id')
			<div class="invalid-feedback">
				{{ $errors->first('country_id')}}
			</div>
		@enderror
	</div>
	<div class="col-md input-group mb-3 @error('state_id') has-error @enderror">
		<div class="input-group-prepend">
			<span class="input-group-text">Estado</span>
		</div>
		<select class="form-control select2 @error('state_id') is-invalid @enderror" name="state_id" id="state">
			@if($estados != null)
	    		@foreach($estados as $c)
	    			<option value="{{$c->id}}" {{(isset($institucion) && $institucion->state_id == $c->id)? 'selected': ''}}> {{$c->description}}</option>
	    		@endforeach
	    	@endif
		</select>
		@error('state_id')
			<div class="invalid-feedback">
				{{ $errors->first('state_id')}}
			</div>
		@enderror
	</div>
</div>
<div class="input-group">
	<div class="col-md input-group mb-3 @error('city') has-error @enderror">
		<div class="input-group-prepend">
			<span class="input-group-text">Ciudad</span>
		</div>
		{!! Form::text('city',  null, ['class' => $errors->has('city') ? 'form-control is.invalid': 'form-control', 'placeholder' => '', 'required']) !!}
		@error('address')
			<div class="invalid-feedback">
				{{ $errors->first('city')}}
			</div>
		@enderror
	</div>
	<div class="col-md-2 input-group mb-3 @error('postal_code') has-error @enderror">
		<div class="input-group-prepend">
			<span class="input-group-text">C.P</span>
		</div>
		{!! Form::text('postal_code',  null, ['class' => $errors->has('postal_code') ? 'form-control is.invalid': 'form-control', 'placeholder' => '']) !!}
		@error('postal_code')
			<div class="invalid-feedback">
				{{ $errors->first('postal_code')}}
			</div>
		@enderror
	</div>
</div>
<div class="input-group">
	<div class="col-md input-group mb-3 @error('phone') has-error @enderror">
		<div class="input-group-prepend">
			<span class="input-group-text">Teléfono</span>
		</div>
		{!! Form::text('phone',  null, ['class' => $errors->has('phone') ? 'form-control is.invalid': 'form-control', 'placeholder' => '']) !!}
		@error('phone')
			<div class="invalid-feedback">
				{{ $errors->first('phone')}}
			</div>
		@enderror
	</div>
	<div class="col-md input-group mb-3 @error('phone2') has-error @enderror">
		<div class="input-group-prepend">
			<span class="input-group-text">Teléfono 2</span>
		</div>
		{!! Form::text('phone2',  null, ['class' => $errors->has('phone2') ? 'form-control is.invalid': 'form-control', 'placeholder' => '']) !!}
		@error('phone2')
			<div class="invalid-feedback">
				{{ $errors->first('phone2')}}
			</div>
		@enderror
	</div>
	<div class="col-md input-group mb-3 @error('fax') has-error @enderror">
		<div class="input-group-prepend">
			<span class="input-group-text">Fax</span>
		</div>
		{!! Form::text('fax',  null, ['class' => $errors->has('fax') ? 'form-control is.invalid': 'form-control', 'placeholder' => '']) !!}
		@error('fax')
			<div class="invalid-feedback">
				{{ $errors->first('fax')}}
			</div>
		@enderror
	</div>
</div>
<div class="input-group">
	<div class="col-md input-group mb-3 @error('email') has-error @enderror">
		<div class="input-group-prepend">
			<span class="input-group-text">Correo</span>
		</div>
		{!! Form::email('email',  null, ['class' => $errors->has('email') ? 'form-control is.invalid': 'form-control', 'placeholder' => 'ejemplo@ejemplo.com']) !!}
		@error('email')
			<div class="invalid-feedback">
				{{ $errors->first('email')}}
			</div>
		@enderror
	</div>
	<div class="col-md input-group mb-3 @error('web_site') has-error @enderror">
		<div class="input-group-prepend">
			<span class="input-group-text">Sitio web</span>
		</div>
		{!! Form::text('web_site',  null, ['class' => $errors->has('web_site') ? 'form-control is.invalid': 'form-control', 'placeholder' => 'http://ejemplo.com.mx']) !!}
		@error('web_site')
			<div class="invalid-feedback">
				{{ $errors->first('web_site')}}
			</div>
		@enderror
	</div>
</div>
<div class="input-group">
	<div class="col-md input-group mb-3 @error('business_activity') has-error @enderror">
		<div class="input-group-prepend">
			<span class="input-group-text">Giro</span>
		</div>
		{!! Form::text('business_activity',  null, ['class' => $errors->has('business_activity') ? 'form-control is.invalid': 'form-control', 'placeholder' => '']) !!}
		@error('business_activity')
			<div class="invalid-feedback">
				{{ $errors->first('business_activity')}}
			</div>
		@enderror
	</div>
	<div class="col-md input-group mb-3 @error('rfc') has-error @enderror">
		<div class="input-group-prepend">
			<span class="input-group-text">RFC</span>
		</div>
		{!! Form::text('rfc',  null, ['class' => $errors->has('rfc') ? 'form-control is.invalid': 'form-control', 'placeholder' => '']) !!}
		@error('rfc')
			<div class="invalid-feedback">
				{{ $errors->first('rfc')}}
			</div>
		@enderror
	</div>
</div>
