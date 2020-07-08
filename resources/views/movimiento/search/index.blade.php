@extends('admin.layout')

@section('title', '- Búsqueda de movimientos')

@section('breadcrumb')
    <li class="breadcrumb-item">
        Movimientos - Búsqueda
    </li>
@endsection

@push('after_all_scripts')
    <script src="{{ asset('admin/js/movimientosBusqueda.js?v=20200104') }}"></script>
@endpush

@section('content')
    @can('ver_movimientos')
        {!! Form::open(['route' => 'movimientos.search.results', 'method' => 'get']) !!}
        <div class="input-group mb-3 justify-content-center alert alert-warning">
            Buscar por:
            <div class="custom-control custom-radio custom-control-inline ml-3">
                <input type="radio" id="radio_institution" name="type" value="institution" class="custom-control-input" checked>
                <label class="custom-control-label" for="radio_institution">Institución</label>
            </div>
            <div class="custom-control custom-radio custom-control-inline">
                <input type="radio" id="radio_exhibition" name="type" value="exhibition" class="custom-control-input">
                <label class="custom-control-label" for="radio_exhibition">Exposición/Ubicación</label>
            </div>
            <div class="custom-control custom-radio custom-control-inline">
                <input type="radio" id="radio_venue" name="type" value="venue" class="custom-control-input">
                <label class="custom-control-label" for="radio_venue">Sede</label>
            </div>
        </div>
        <div class="m-auto">
            <div id="select_institution">
                <select class="select2" name="institution">
                    @foreach ($institutions as $key => $institution)
                        <option value="{{$institution->id}}">{{$institution->name}}</option>
                    @endforeach
                </select>
            </div>
            <div id="select_exhibition" style="display:none;">
                <select class="select2" name="exhibition">
                    @foreach ($exhibitions as $key => $exhibition)
                        <option value="{{$exhibition->id}}">{{$exhibition->name}}</option>
                    @endforeach
                </select>
            </div>
            <div id="select_venue" style="display:none;">
                <select class="select2" name="venue">
                    @foreach ($venues as $key => $venue)
                        <option value="{{$venue->id}}">{{$venue->name}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        {{-- ACCIONES --}}
        <div class="text-center mt-3">
            <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> Buscar</button>
        </div>
        {!! Form::close() !!}
    @endcan
    @include('flash-toastr::message')
@endsection
