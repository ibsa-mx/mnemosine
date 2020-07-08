@extends('admin.layout')

@section('title', '- Reportes')

@section('breadcrumb')
    <li class="breadcrumb-item">
        Reportes
    </li>
@endsection

@section('content')
    @can('agregar_reportes')
        <div class="row flex-row-reverse mr-1">
            <a class="btn btn-outline-primary btn-sm" href="{{ route('reportes.create') }}" role="button" data-toggle="tooltip" title="Nuevo reporte"><i class="fas fa-plus"></i>  Agregar</a>
        </div><br>
    @endcan

    <div>
        <table id="data-table" class="table table-bordered dt-responsive">
            <thead>
                <tr>
                    <th>Nombre del Informe</th>
                    <th>Descripción</th>
                    <th>Fecha de creación</th>
                    <th>Fecha de modificación</th>
                    @canany(['ver_reportes', 'editar_reportes', 'eliminar_reportes'])
                        <th>Acciones</th>
                    @endcanany
                </tr>
            </thead>
            <tbody>
                @foreach($reports as $r)
                <tr @if($r->lending_list) class="bg-gray-200" @endif>
                    <td>
                        {{$r->name}}
                        @if($r->lending_list)
                            <br/>
                            <span class="badge badge-warning float-right mb-n2 mr-n2">Préstamo</span>
                        @endif
                    </td>
                    <td>{{$r->description}}</td>
                    <td data-order="{{$r->created_at->timestamp}}">
                        {{$r->created_at->locale('es_MX')->isoFormat('LL')}} por {{$r->creator->name}}
                    </td>
                    <td data-order="{{$r->updated_at->timestamp}}">
                        {{$r->updated_at->locale('es_MX')->isoFormat('LL')}} por {{$r->updater->name ?? $r->creator->name}}
                    </td>
                      @canany(['ver_reportes', 'editar_reportes', 'eliminar_reportes'])
                        <td class="text-center align-middle">
                            <a class="btn btn-outline-primary btn-sm" href="{{route('reportes.show', ['id' => $r->id]) }}" data-toggle="tooltip" title="Ver reporte"><i class="far fa-eye"></i></a>
                            @include('shared._actions', [
                                'entity' => 'reportes',
                                'id' => $r->id,
                                'singular' => 'reporte',
                                'search' => '0'
                            ])
                        </td>
                      @endcanany
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@if ($errors->any())
<script type="text/javascript">
   $('document').ready(function(){
   toastr.options = $.parseJSON('{"closeButton":false,"debug":false,"newestOnTop":true,"progressBar":true,"positionClass":"toast-top-right","preventDuplicates":false,"onclick":null,"showDuration":"300","hideDuration":"1000","timeOut":"5000","extendedTimeOut":"1000","showEasing":"swing","hideEasing":"linear","showMethod":"fadeIn","hideMethod":"fadeOut"}');
    toastr["error"]("Hubo un error al intentar crear un reporte.", "");
   });
</script>
@endif
@include('flash-toastr::message')
@endsection
