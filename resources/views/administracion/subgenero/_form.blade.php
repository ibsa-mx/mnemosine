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
<div class="input-group mb-3 @error('description') has-error @enderror">
    <div class="input-group-prepend">
        <span class="input-group-text">Descripción</span>
    </div>
    {!! Form::textarea('description', null, ['class' => $errors->has('description') ? 'form-control is-invalid' : 'form-control', 'rows' => '4']) !!}
    @error('description')
        <div class="invalid-feedback">
            {{ $errors->first('description') }}
        </div>
    @enderror
</div>
