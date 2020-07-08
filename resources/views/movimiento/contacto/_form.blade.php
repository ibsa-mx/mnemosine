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

<div class="row">
	<div class="col-md-8">
		<div class="input-group">
			<div class="col-md input-group mb-3 @error('name') has-error @enderror">
				<div class="input-group-prepend">
					<span class="input-group-text">Nombre</span>
				</div>
			    {!! Form::text('name', null, ['class' => $errors->has('name') ? 'form-control is-invalid' : 'form-control', 'placeholder' => '', 'required']) !!}
			    @error('name')
			        <div class="invalid-feedback">
			            {{ $errors->first('name') }}
			        </div>
			    @enderror
			</div>
			<div class="col-md input-group mb-3 @error('last_name') has-error @enderror">
				<div class="input-group-prepend">
					<span class="input-group-text">Apellido paterno</span>
				</div>
			    {!! Form::text('last_name', null, ['class' => $errors->has('last_name') ? 'form-control is-invalid' : 'form-control', 'placeholder' => '', 'required']) !!}
			    @error('last_name')
			        <div class="invalid-feedback">
			            {{ $errors->first('last_name') }}
			        </div>
			    @enderror
			</div>
		</div>
		<div class="input-group">
			<div class="col-md input-group mb-3 @error('m_last_name') has-error @enderror">
				<div class="input-group-prepend">
					<span class="input-group-text">Apellido materno</span>
				</div>
			    {!! Form::text('m_last_name', null, ['class' => $errors->has('m_last_name') ? 'form-control is-invalid' : 'form-control', 'placeholder' => '']) !!}
			    @error('m_last_name')
			        <div class="invalid-feedback">
			            {{ $errors->first('m_last_name') }}
			        </div>
			    @enderror
			</div>
			<div class="col-md input-group mb-3 @error('treatment_title') has-error @enderror">
				<div class="input-group-prepend">
					<span class="input-group-text">Título</span>
				</div>
			    <select class="input-group form-control select2" name="treatment_title">
			    	<option selected="selected" disabled="disabled">Seleccione una opción</option>
			    	@if($titulos != null)
			    		@foreach($titulos as $titulo)
						<option value="{{$titulo->id}}" {{(isset($contacto) && $contacto->treatment_title == $titulo->id) ? 'selected': ''}}>{{$titulo->title}}</option>
						@endforeach
			    	@endif
			    </select>
			</div>
		</div>
		<div class="input-group">
			<div class="col-md input-group mb-3 @error('phone') has-error @enderror">
				<div class="input-group-prepend">
					<span class="input-group-text">Teléfono</span>
				</div>
			    {!! Form::text('phone', null, ['class' => $errors->has('phone') ? 'form-control is-invalid' : 'form-control', 'placeholder' => '', 'required']) !!}
			    @error('phone')
			        <div class="invalid-feedback">
			            {{ $errors->first('phone') }}
			        </div>
			    @enderror
			</div>
			<div class="col-md input-group mb-3 @error('phone2') has-error @enderror">
				<div class="input-group-prepend">
					<span class="input-group-text">Teléfono 2</span>
				</div>
			    {!! Form::text('phone2', null, ['class' => $errors->has('phone2') ? 'form-control is-invalid' : 'form-control', 'placeholder' => '']) !!}
			    @error('phone2')
			        <div class="invalid-feedback">
			            {{ $errors->first('phone2') }}
			        </div>
			    @enderror
			</div>
		</div>
		<div class="col-md input-group mb-3 @error('email') has-error @enderror">
			<div class="input-group-prepend">
				<span class="input-group-text">Correo</span>
			</div>
		    {!! Form::text('email', null, ['class' => $errors->has('email') ? 'form-control is-invalid' : 'form-control', 'placeholder' => '']) !!}
		    @error('email')
		        <div class="invalid-feedback">
		            {{ $errors->first('email') }}
		        </div>
		    @enderror
		</div>
	</div>
	<span class="row border border-left border-info"></span>
	<div class="col-md-4">
		<div class="col-md input-group mb-3 @error('institution_id') has-error @enderror">
			<div class="input-group-prepend">
				<span class="input-group-text">Institución</span>
			</div>
		    <select class="input-group form-control select2 @error('institution_id') is-invalid @enderror" name="institution_id" id="institution_id" required="required">
		    	<option selected="selected" disabled="disabled">Seleccione una opción</option>
	    		@if($instituciones != null)
		    		@foreach($instituciones as $i)
					<option value="{{$i->id}}" {{(isset($contacto) && $contacto->institution_id == $i->id) ? 'selected': ''}}> {{$i->name}}</option>
					@endforeach
		    	@endif
		    </select>
		</div>
		<div class="col-md input-group mb-3 @error('position') has-error @enderror">
			<div class="input-group-prepend">
				<span class="input-group-text">Cargo</span>
			</div>
		    {!! Form::text('position', null, ['class' => $errors->has('position') ? 'form-control is-invalid' : 'form-control', 'placeholder' => '']) !!}
		    @error('position')
		        <div class="invalid-feedback">
		            {{ $errors->first('position') }}
		        </div>
		    @enderror
		</div>
		<div class="col-md input-group mb-3 @error('department') has-error @enderror">
			<div class="input-group-prepend">
				<span class="input-group-text">Departamento</span>
			</div>
		    {!! Form::text('department', null, ['class' => $errors->has('department') ? 'form-control is-invalid' : 'form-control', 'placeholder' => '']) !!}
		    @error('department')
		        <div class="invalid-feedback">
		            {{ $errors->first('department') }}
		        </div>
		    @enderror
		</div>
	</div>
</div>
