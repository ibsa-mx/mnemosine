@extends('admin.layout')


@section('title', '- Nuevo género')

@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{route('generos.index')}}">Géneros</a>
    </li>
    <li class="breadcrumb-item active">
        Nuevo género
    </li>
@endsection

@section('content')
<div class="container">
    {!! Form::open(['route' => 'generos.store', 'files' => true, 'id' => 'frmGenero']) !!}
        @include('administracion.genero._form')
        <div class="text-center mb-3">
            <a href="{{ route('generos.index') }}" class="btn btn-secondary">Cancelar</a>
            {!! Form::submit('Crear género', ['class' => 'btn btn-primary']) !!}
        </div>
    {!! Form::close() !!}
</div>
@endsection
