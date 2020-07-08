@extends('admin.layout')

@section('title', '- Inventario, editando pieza')

@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{route('inventario.index')}}">Inventario</a>
    </li>
    <li class="breadcrumb-item active">
        Editando pieza
    </li>
@endsection

@section('content')
<div class="container">
    {!! Form::model($piece, ['method' => 'PUT', 'route' => ['inventario.update',  $piece->id ], 'files' => true, 'id' => 'frmPieza']) !!}
        @include('inventario._form')

        <div class="text-center mb-3">
            <a href="{{ route('inventario.index') }}" class="btn btn-secondary">Cancelar</a>
            {!! Form::submit('Guardar cambios', ['class' => 'btn btn-primary']) !!}
        </div>
    {!! Form::close() !!}
</div>
@endsection
