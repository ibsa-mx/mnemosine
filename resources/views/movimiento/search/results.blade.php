@extends('admin.layout')

@section('title', '- Búsqueda de movimientos')

@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{ route('movimientos.search.index') }}">Movimientos - Búsqueda</a>
    </li>
    <li class="breadcrumb-item active">
        {!! ucfirst($types[$request->type]) !!}: {{$type->name}}
    </li>
@endsection

@section('content')
    @can('ver_movimientos')
        <div class="bg-primary text-white px-3 py-1 mb-3">
            <a href="{{ route('movimientos.search.resultsExcel') }}?type={{$request['type']}}&{{$request['type']}}={{$request[$request['type']]}}" class="mt-1 btn btn-sm btn-dark float-right" rel="tooltip" title="Descargar Excel"><i class="far fa-file-excel"></i> Descargar</a>
            <span class="h3">Búsqueda de movimientos por {{$types[$request->type]}}</span>
            <br />
            {!! ucfirst($types[$request->type]) !!}: {{$type->name}}
        </div>
        @isset($movements)
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Núms. de inventario</th>
                        <th>Fecha salida</th>
                        <th>Fecha entrada</th>
                        <th>Institución</th>
                        <th>Ubicación / Exposición</th>
                        <th>Sede</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($movements as $movement)
                        <tr>
                            <td class="w-25">
                                @isset($movement->pieces)
                                    @foreach ($movement->pieces as $key => $piece)
                                        <a href="{{route('consultas.detalle', $piece->id)}}" class="badge badge-primary" target="_blank">{{$piece->inventory_number}}</a>
                                    @endforeach
                                @endisset
                            </td>
                            <td>{!! !empty($movement->departure_date) ? $movement->departure_date->locale('es_MX')->isoFormat('LL') : '' !!}</td>
                            <td>{!! !empty($movement->arrival_date) ? $movement->arrival_date->locale('es_MX')->isoFormat('LL') : '' !!}</td>
                            <td>{{!is_null($movement->institutions) ? implode(", ", $movement->institutions->pluck('name')->toArray()) : '-'}}</td>
                            <td>{{$movement->exhibition['name']}}</td>
                            <td>{{!is_null($movement->venue) ? implode(", ", $movement->venue->pluck('name')->toArray()) : '-'}}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="alert alert-warning mt-3">No se han registrado movimientos</div>
        @endisset
        {{ $movements->appends(['type' => $request->type, $request->type => $request->{$request->type}])->links() }}
    @endcan
@endsection
