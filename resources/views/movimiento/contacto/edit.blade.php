@extends('admin.layout')
@section('title', '- Contactos, editando contacto')
@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{route('contactos.index')}}">Contactos</a>
    </li>
    <li class="breadcrumb-item active">
        Editando contacto
    </li>
@endsection
@section('content')
<div class="card card-accent-info">
  <h5 class="card-header">Editar datos personales</h5>
  <div class="card-body">
    {!! Form::model($contacto, ['method' => 'PUT', 'route' => ['contactos.update',  $contacto->id ], 'files' => true, 'id' => 'frmContacto']) !!}
        @include('movimiento.contacto._form')
        <div class="text-center mb-3">
            <a href="{{ route('contactos.index') }}" class="btn btn-secondary">Cancelar</a>
            {!! Form::submit('Guardar cambios', ['class' => 'btn btn-primary']) !!}
        </div>
    {!! Form::close() !!}
  </div>
</div>
@endsection
