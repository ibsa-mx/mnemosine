@extends('admin.layout')

@section('title', '- Restauración, editar datos')

@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{route('restauracion.index')}}">Restauración</a>
    </li>
    <li class="breadcrumb-item active">
        Creando restauración para la pieza {{$piece->inventory_number}}
    </li>
@endsection

@section('content')
    {!! Form::open(['route' => 'restauracion.store', 'files' => true, 'id' => 'frmRestauracion']) !!}
        @include('restauracion._form')
        <div class="text-center mb-3">
            <a href="{{ route('restauracion.index') }}" class="btn btn-secondary">Cancelar</a>
            {!! Form::submit('Guardar cambios', ['class' => 'btn btn-primary']) !!}
        </div>
    {!! Form::close() !!}
@endsection
