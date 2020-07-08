@extends('admin.layout')

@section('title', '- Movimientos')

@section('breadcrumb')
    <li class="breadcrumb-item">
        Movimientos
    </li>
@endsection

@section('content')
    @can('agregar_movimientos')
        <div class="page-action text-right mb-3">
            <a href="{{ route('movimientos.create') }}" class="btn btn-outline-primary btn-sm pull-right" rel="tooltip" title="Nuevo movimiento"><i class="fas fa-plus"></i> Agregar</a>
        </div>
    @endcan

    <table id="data-table" class="table table-striped table-bordered dt-responsive" style="width:100%">
        <thead>
            <tr>
                <th>Ubicación / Exposición</th>
                <th>Institución</th>
                <th>Responsable de movimiento</th>
                <th>Fecha de Salida</th>
                <th>Piezas</th>
                @canany(['ver_movimientos', 'editar_movimientos', 'eliminar_movimientos'])
                    <th>Acciones</th>
                @endcanany
            </tr>
        </thead>
        <tbody>
            @foreach($movimientos as $movimiento)
                <tr>
                    <td>
                        @if($expos != null)
                            @foreach($expos as $e)
                                @if($movimiento->exhibition_id == $e->id)
                                    {{$e->name}}
                                @endif
                            @endforeach
                        @endif
                    </td>
                    <td>
                        @if($instituciones != null)
                            @php
                            $institucionesStr = $instituciones->whereIn('id', explode(',', $movimiento->institution_ids))->pluck('name')->join(', ');
                            @endphp
                            {{$institucionesStr}}
                        @endif
                    </td>
                    <td>
                        @if($contactos != null)
                            @php
                            $contactosNombres = $contactos->whereIn('id', explode(',', $movimiento->contact_ids))->pluck('name', 'id')->toArray();
                            $contactosApellidos = $contactos->whereIn('id', explode(',', $movimiento->contact_ids))->pluck('last_name', 'id')->toArray();
                            $contactosArr = array();
                            foreach($contactosNombres as $idx => $nombre){
                                $contactosArr[] = $nombre . " " . $contactosApellidos[$idx];
                            }
                            $contactosStr = implode(", ", $contactosArr);
                            @endphp
                            {{$contactosStr}}
                        @endif
                    </td>
                    <td data-order="{{$movimiento->departure_date->timestamp}}">
                        {{ $movimiento->departure_date->locale('es_MX')->isoFormat('LL') }}
                    </td>
                    <td class="w-25">
                        @isset($movimiento->pieces)
                            @foreach ($movimiento->pieces as $key => $piece)
                                <a href="{{route('consultas.detalle', $piece->id)}}" class="badge badge-primary" target="_blank">{{$piece->inventory_number}}</a>
                            @endforeach
                        @endisset
                    </td>
                    @canany(['ver_movimientos', 'editar_movimientos', 'eliminar_movimientos'])
                        <td class="text-center">
                            @if(!$movimiento->authorized_by_collections && !$movimiento->authorized_by_exhibitions)
                                @can ('editar_movimientos')
                                    {{-- PASO 1 --}}
                                    <a href="{{route('movimientos.edit', ['id' => $movimiento->id])}}" class="btn btn-outline-primary btn-sm" title="Paso 1 - Datos generales"><i class="far fa-edit"></i></a>

                                    {{-- PASO 2 --}}
                                    <a href="{{route('movimientos.show', ['id' => $movimiento->id])}}" class="btn btn-outline-primary btn-sm" title="Paso 2 - Selección de piezas"><i class="fas fa-pencil-alt"></i></a>
                                @endcan
                            @endif
                            {{-- PASO 3 --}}
                            @if(($movimiento->pieces_ids != null) && ($movimiento->type_arrival == null))
                                <a href="{{route('movimientos.resumen_authorizar', ['id' => $movimiento->id])}}" class="btn btn-outline-primary btn-sm" title="Paso 3 - Información del movimiento"><i class="fas fa-check"></i></a>
                            @endif

                            @can('editar_movimientos')
                                @if($movimiento->authorized_by_collections || $movimiento->authorized_by_exhibitions)
                                    @if($movimiento->pieces_ids != $movimiento->pieces_ids_arrived)
                                        <a href="{{route('movimientos.return_pieces', ['id' => $movimiento->id])}}" class="btn btn-outline-primary btn-sm" title="Paso 4 - Regresar piezas"><i class="fas fa-undo-alt"></i></a>
                                    @endif
                                @endif
                            @endcan
                        </td>
                    @endcanany
                </tr>
            @endforeach
        </tbody>
    </table>
    {{ $movimientos->links() }}
    @include('flash-toastr::message')
@endsection
