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
    {!! Form::text('name', null, ['class' => $errors->has('name') ? 'form-control is-invalid' : 'form-control', 'placeholder' => 'Escribe el nombre de la sede', 'required']) !!}
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
    {!! Form::text('address', null, ['class' => $errors->has('address') ? 'form-control is-invalid' : 'form-control', 'placeholder' => 'Calle/Avenida/, N°, Ciudad, Estado...', 'required']) !!}
    @error('address')
        <div class="invalid-feedback">
            {{ $errors->first('address') }}
        </div>
    @enderror
</div>
<div class="col-md input-group mb-3 @error('institution_id') has-error @enderror"> 
	<div class="input-group-prepend">
		<span class="input-group-text">Institución</span>
	</div>
    <select class="form-control form-group select2 @error('institution_id') is-invalid @enderror" name="institution_id" id="institucion" required="required">
    	@if($instituciones != null)
    		<option selected="selected" disabled="disabled">--Seleccione una opción--</option>
    		@foreach($instituciones as $i)
    			<option value="{{$i->id}}" {{(isset($sede) && $sede->institution_id == $i->id)? 'selected': ''}}> {{$i->name}}</option>
    		@endforeach
    	@endif
    </select>
    @error('institution_id')
        <div class="invalid-feedback">
            {{ $errors->first('institution_id') }}
        </div>
    @enderror
</div>
<div class="col-md input-group mb-3 @error('contact_id') has-error @enderror"> 
	<div class="input-group-prepend">
		<span class="input-group-text">Contacto</span>
	</div>
    <select class="form-control form-group select2 @error('contact_id') is-invalid @enderror" name="contact_id" id="contacto" required="required">
    	@if($contactos != null)
    		@foreach($contactos as $c)
    			<option value="{{$c->id}}" {{(isset($sede) && $sede->contact_id == $c->id)? 'selected': ''}}> {{$c->name}} {{$c->last_name}}</option>
    		@endforeach
    	@endif
    </select>
    @error('contact_id')
        <div class="invalid-feedback">
            {{ $errors->first('contact_id') }}
        </div>
    @enderror
</div>
