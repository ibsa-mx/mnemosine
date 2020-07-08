@extends('admin.layout')
@section('title', '- Contactos, nuevo contacto')
@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{route('contactos.index')}}">Contacto</a>
    </li>
    <li class="breadcrumb-item active">
        Nuevo contacto
    </li>
@endsection
@section('content')
<div class="card card-accent-info">
  <h5 class="card-header">Datos personales</h5>
  <div class="card-body">
    {!! Form::open(['route' => 'contactos.store']) !!}
        @include('movimiento.contacto._form')
        <div class="text-center mb-3">
            <a href="{{ route('contactos.index') }}" class="btn btn-secondary">Cancelar</a>
            {!! Form::submit('Crear contacto', ['class' => 'btn btn-primary']) !!}
        </div>
    {!! Form::close() !!}
  </div>
</div>
@endsection
