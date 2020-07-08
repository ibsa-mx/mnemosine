@extends('layouts.login')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-sm-10 col-md-8 col-lg-6 px-md-3 px-lg-5 py-0">
                <div class="card card-accent-primary shadow">
                    <div class="card-body py-3">
                        <div class="text-center">
                            <img src="{{ asset('admin/images/Franz-mayer.png') }}" class="w-25" alt="" />
                        </div>
                        <hr/>
                        {!! Form::open(['route' => 'login']) !!}
                            <div class="input-group mb-4 @if ($errors->has('email')) has-error @endif">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="icon-user"></i></span>
                                </div>
                                {!! Form::email('email', old('email'), ['class' => $errors->has('email') ? 'form-control is-invalid' : 'form-control', 'placeholder' => 'Correo electrónico', 'required', 'autofocus']) !!}
                                @if ($errors->has('email'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('email') }}
                                    </div>
                                @endif
                            </div>
                            <div class="input-group mb-4 @if ($errors->has('password')) has-error @endif">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="icon-lock"></i></span>
                                </div>
                                {!! Form::password('password', ['class' => $errors->has('password') ? 'form-control is-invalid' : 'form-control', 'placeholder' => 'Contraseña', 'required']) !!}
                                @if ($errors->has('password'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('password') }}
                                    </div>
                                @endif
                            </div>
                            <div class="row">
                                <div class="col-12 col-md-6 text-center">
                                    <label class="switch switch-label switch-pill switch-primary align-bottom">
                                        <input class="switch-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                        <span class="switch-slider" data-checked="✓" data-unchecked="✕"></span>
                                    </label>
                                    <label for="remember" class="align-top">
                                        {{ __('Recordarme') }}
                                    </label>
                                </div>
                                <div class="col-12 col-md-6 text-center">
                                    <button type="submit" class="btn btn-primary px-4">{{ __('Iniciar sesión') }}</button>
                                </div>
                            </div>

                        {!! Form::close() !!}
                    </div>
                    <div class="card-footer">
                        @if (Route::has('password.request'))
                            {{-- <div class="text-right">
                                ¿Olvidaste tu contraseña?  <a href="{{route('password.request')}}">Da clic aquí</a>
                            </div> --}}
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
