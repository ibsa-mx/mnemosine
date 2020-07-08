@extends('admin.layout')

@section('title', '- Nuevo usuario')

@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{ route('usuarios.index') }}">Usuarios</a>
    </li>
    <li class="breadcrumb-item active">
        Nuevo usuario
    </li>
@endsection

@section('content')
    <div class="container">
        {!! Form::open(['route' => ['usuarios.store'] ]) !!}
            @include('usuarios._form')
            <div class="text-center mb-3">
                <a href="{{ route('usuarios.index') }}" class="btn btn-secondary">Cancelar</a>
                {!! Form::submit('Crear usuario', ['class' => 'btn btn-primary']) !!}
            </div>
        {!! Form::close() !!}
    </div>
@endsection
