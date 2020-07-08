@extends('admin.layout')

@section('title', '- Instituciones, nueva institucion')

@section('assets')
    <script src="{{ asset('admin/js/movimientos.js') }}"></script>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{route('instituciones.index')}}">Instituciones</a>
    </li>
    <li class="breadcrumb-item active">
        Nueva institución
    </li>
@endsection
@section('content')
<div class="card card-accent-info">
    <h5 class="card-header mb-3">Institución</h5>
    {!! Form::open(['route' => 'instituciones.store', 'files' => true]) !!}
        @include('movimiento.institucion._form')
        <div class="text-center mb-3">
            <a href="{{ route('instituciones.index') }}" class="btn btn-secondary">Cancelar</a>
            {!! Form::submit('Crear institución', ['class' => 'btn btn-primary']) !!}
        </div>
    {!! Form::close() !!}
</div>
@endsection
