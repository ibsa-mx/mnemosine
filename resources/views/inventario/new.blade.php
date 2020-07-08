@extends('admin.layout')

@section('title', '- Inventario, nueva pieza')

@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{route('inventario.index')}}">Inventario</a>
    </li>
    <li class="breadcrumb-item active">
        Nueva pieza
    </li>
@endsection

@section('content')
<div class="container">
    {!! Form::open(['route' => 'inventario.store', 'files' => true, 'id' => 'frmPieza']) !!}
        @include('inventario._form')
        <div class="text-center mb-3">
            <a href="{{ route('inventario.index') }}" class="btn btn-secondary">Cancelar</a>
            {!! Form::submit('Crear pieza', ['class' => 'btn btn-primary']) !!}
        </div>
    {!! Form::close() !!}
</div>
@endsection
