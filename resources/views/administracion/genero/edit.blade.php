@extends('admin.layout')

@section('title', '- Modificar género')

@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{route('generos.index')}}">Géneros</a>
    </li>
    <li class="breadcrumb-item active">
        Modificar género
    </li>
@endsection

@section('content')
<div class="container">
    {!! Form::model($genero, ['method' => 'PUT', 'route' => ['generos.update',  $genero->id ], 'files' => true, 'id' => 'frmGenero']) !!}
        @include('administracion.genero._form')
        <div class="text-center mb-3">
            <a href="{{ route('generos.index') }}" class="btn btn-secondary">Cancelar</a>
            {!! Form::submit('Modificar género', ['class' => 'btn btn-primary']) !!}
        </div>
    {!! Form::close() !!}
</div>
@endsection
