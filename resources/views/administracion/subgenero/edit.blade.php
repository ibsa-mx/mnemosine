@extends('admin.layout')

@section('title', '- Modificar subgénero')

@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{route('generos.index')}}">Géneros</a>
    </li>
    <li class="breadcrumb-item">
        <a href="{{route("subgeneros.show", $gender->id)}}">Subgéneros de <em>"{{$gender->title}}"</em></a>
    </li>
    <li class="breadcrumb-item active">
        Modificar subgénero
    </li>
@endsection

@section('content')
<div class="container">
    {!! Form::model($subgenero, ['method' => 'PUT', 'route' => ['subgeneros.update',  $subgenero->id ], 'files' => true, 'id' => 'frmSubgenero']) !!}
        @include('administracion.genero._form')
        <div class="text-center mb-3">
            <a href="{{route("subgeneros.show", $gender->id)}}" class="btn btn-secondary">Cancelar</a>
            {!! Form::submit('Modificar subgénero', ['class' => 'btn btn-primary']) !!}
        </div>
    {!! Form::close() !!}
</div>
@endsection
