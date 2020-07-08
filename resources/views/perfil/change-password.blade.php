@extends('admin.layout')

@section('title', '- Cambiar contraseña')

@section('breadcrumb')
    <li class="breadcrumb-item active">
        Cambiar contraseña
    </li>
@endsection

@section('content')
    <div class="container">
        <div class="w-75 m-auto">
            {!! Form::open(['route' => 'perfil.updatePassword', 'method' => 'patch']) !!}

            <div class="input-group mb-5 @if ($errors->has('current_password')) has-error @endif">
                <div class="input-group-prepend">
                    <span class="input-group-text">Contraseña actual</span>
                </div>
                {!! Form::password('current_password', ['class' => $errors->has('current_password') ? 'form-control is-invalid' : 'form-control', 'required']) !!}
                @if ($errors->has('current_password'))
                    <div class="invalid-feedback">
                        {{ $errors->first('current_password') }}
                    </div>
                @endif
            </div>

            <div class="input-group mb-3 @if ($errors->has('new_password')) has-error @endif">
                <div class="input-group-prepend">
                    <span class="input-group-text">Nueva contraseña</span>
                </div>
                {!! Form::password('new_password', ['class' => $errors->has('new_password') ? 'form-control is-invalid' : 'form-control', 'required']) !!}
                @if ($errors->has('new_password'))
                    <div class="invalid-feedback">
                        {{ $errors->first('new_password') }}
                    </div>
                @endif
            </div>

            <div class="input-group mb-3 @if ($errors->has('confirm_password')) has-error @endif">
                <div class="input-group-prepend">
                    <span class="input-group-text">Confirmar contraseña</span>
                </div>
                {!! Form::password('confirm_password', ['class' => $errors->has('confirm_password') ? 'form-control is-invalid' : 'form-control', 'required']) !!}
                @if ($errors->has('confirm_password'))
                    <div class="invalid-feedback">
                        {{ $errors->first('confirm_password') }}
                    </div>
                @endif
            </div>

            <div class="text-center mb-3">
                <a href="{{ route('home') }}" class="btn btn-secondary">Cancelar</a>
                {!! Form::submit('Cambiar contraseña', ['class' => 'btn btn-primary']) !!}
            </div>
            {!! Form::close() !!}
        </div>
    </div>
@endsection
