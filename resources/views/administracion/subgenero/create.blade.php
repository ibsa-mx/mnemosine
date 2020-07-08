@extends('admin.layout')

@section('title', '- Nuevo subgénero')

@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{route('generos.index')}}">Géneros</a>
    </li>
    <li class="breadcrumb-item">
        <a href="{{route("subgeneros.show", $gender->id)}}">Subgéneros de <em>"{{$gender->title}}"</em></a>
    </li>
    <li class="breadcrumb-item active">
        Nuevo subgénero
    </li>
@endsection

@section('content')
<div class="container">
    {!! Form::open(['route' => 'subgeneros.store', 'files' => true, 'id' => 'frmSubgenero']) !!}
        @include('administracion.subgenero._form')

        {!! Form::hidden('gender_id', $gender->id) !!}
        <div class="text-center mb-3">
            <a href="{{route("subgeneros.show", $gender->id)}}" class="btn btn-secondary">Cancelar</a>
            {!! Form::submit('Crear subgénero', ['class' => 'btn btn-primary']) !!}
        </div>
    {!! Form::close() !!}
</div>
@endsection
