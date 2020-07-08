@extends('admin.layout')
@section('title', '- Exposiciones')
@section('breadcrumb')
    <li class="breadcrumb-item">
        Exposiciones
    </li>
@endsection
@section('content')
    @can('agregar_movimientos')
        <div class="page-action text-right mb-3">
            <a href="{{ route('exposiciones.create') }}" class="btn btn-outline-primary btn-sm pull-right" rel="tooltip" title="Nueva exposición"><i class="fas fa-plus"></i> Agregar</a>
        </div>
    @endcan
    <table id="data-table" class="table table-striped table-bordered dt-responsive" style="width:100%">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Institución</th>
                <th>Contacto</th>
                @canany(['editar_movimientos', 'eliminar_movimientos'])
                    <th>Acciones</th>
                @endcanany
            </tr>
        </thead>
        <tbody>
            @foreach($exposiciones as $item)
                <tr>
                    <td>{{$item->name}}</td>
                    <td>
                        @if($instituciones != null)
                            @foreach($instituciones as $i)
                                @if($item->institution_id == $i->id)
                                    {{$i->name}}
                                @endif
                            @endforeach
                        @endif
                    </td>
                    <td>
                        @if($contactos != null)
                            @foreach($contactos as $i)
                                @if($item->contact_id == $i->id)
                                    {{$i->name ?? ''}} {{ $i->last_name ?? ''}}
                                @endif
                            @endforeach
                        @endif
                    </td>
                    @canany(['editar_movimientos', 'eliminar_movimientos'])
                        <td class="text-center">
                            @include('shared._actions', [
                                'entity' => 'exposiciones',
                                'parentModule' => 'movimientos',
                                'id' => $item->id,
                                'singular' => 'exposición',
                                'search' => '0'
                            ])
                        </td>
                    @endcanany
                </tr>
            @endforeach
        </tbody>
    </table>
    @include('flash-toastr::message')
@endsection
