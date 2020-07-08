<div class="input-group mb-3 @if ($errors->has('name')) has-error @endif">
    <div class="input-group-prepend">
        <span class="input-group-text" id="nombre-text"><i class="fas fa-signature mr-2"></i> Nombre</span>
    </div>
    {!! Form::text('name', null, ['class' =>  $errors->has('name') ? 'form-control is-invalid' : 'form-control', 'placeholder' => '', 'aria-label' => 'Nombre', 'aria-describedby' => 'nombre-text', 'required']) !!}
    @if ($errors->has('name'))
        <div class="invalid-feedback">
            {{ $errors->first('name') }}
        </div>
    @endif
</div>



<div class="input-group mb-3 @if ($errors->has('email')) has-error @endif">
    <div class="input-group-prepend">
        <span class="input-group-text" id="correo-text"><i class="fas fa-at mr-2"></i> Correo electrónico</span>
    </div>
    {!! Form::email('email', null, ['class' => $errors->has('email') ? 'form-control is-invalid' : 'form-control', 'placeholder' => '', 'aria-label' => 'Correo electrónico', 'aria-describedby' => 'correo-text', 'required']) !!}
    @if ($errors->has('email'))
        <div class="invalid-feedback">
            {{ $errors->first('email') }}
        </div>
    @endif
</div>

<div class="input-group mb-3 @if ($errors->has('password')) has-error @endif">
    <div class="input-group-prepend">
        <span class="input-group-text" id="contrasenia-text"><i class="fas fa-key mr-2"></i> Contraseña</span>
    </div>
    {!! Form::password('password', ['class' => $errors->has('password') ? 'form-control is-invalid' : 'form-control', 'placeholder' => '', 'aria-label' => 'Contraseña', 'aria-describedby' => 'contrasenia-text']) !!}
    @if ($errors->has('password'))
        <div class="invalid-feedback">
            {{ $errors->first('password') }}
        </div>
    @endif
</div>

<div class="input-group mb-3 @if ($errors->has('roles')) has-error @endif">
    <div class="input-group-prepend">
        <span class="input-group-text" id="roles-select"><i class="fas fa-users mr-2"></i> Rol de usuario</span>
    </div>
    {!! Form::select('roles[]', $roles, isset($user) ? $user->roles->pluck('id')->toArray() : null, ['class' => $errors->has('roles') ? 'form-control select2-multiple is-invalid' : 'form-control select2-multiple', 'aria-label' => 'Roles', 'aria-describedby' => 'roles-select', 'multiple', 'required']) !!}
    @if ($errors->has('roles'))
        <div class="invalid-feedback">
            {{ $errors->first('roles') }}
        </div>
    @endif
</div>

@if(isset($user))
    @include('shared._permissions', ['closed' => 'true', 'model' => $user ])
@endif
