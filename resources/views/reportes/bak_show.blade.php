@extends('admin.layout')

@section('title', '- Ver reporte')

@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{route('reportes.index')}}">Reportes</a>
    </li>
    <li class="breadcrumb-item active">
        Ver reporte
    </li>
@endsection

@push('after_all_scripts')
    <script src="{{ asset('admin/js/cedulas.js') }}"></script>
    <script src="{{ asset('admin/js/reportesShow.js') }}"></script>
    <script src="{{asset('admin/vendors/lightbox-master/dist/ekko-lightbox.min.js')}}"></script>
    <script type="text/javascript">
    $(function() {
        $(document).on('click', '[data-toggle="lightbox"]', function(event) {
            event.preventDefault();
            $(this).ekkoLightbox({
                alwaysShowClose: true,
                showArrows: true,
                onContentLoaded: function(ev) {
        			setTimeout(function() {
                        var toAppend = $('<a href="'+ $('.ekko-lightbox').find('.img-fluid').prop('src') +'" class="btn btn-success mr-2" download><i class="fas fa-download"></i></a>');
        				$('.ekko-lightbox').find('.modal-footer').prepend(toAppend);
        			}, 1000);
        		}
            });
        });
    });
    </script>
@endpush

@push('script_head')
    <script src="{{asset('vendor/datatables/buttons.server-side.js')}}"></script>
@endpush

@push('after_all_styles')
    <link href="{{asset('admin/vendors/lightbox-master/dist/ekko-lightbox.css')}}" rel="stylesheet">
@endpush

@section('content')
    <div class="bg-primary text-white px-3 py-1 mb-3">
        <div class="h3">
            Reporte: {{$reporte->name}}
            @can('editar_reportes')
                <a href="{{ route('reportes.edit', ['id' => $reporte->id]) }}" class="mt-1 btn btn-sm btn-dark float-right" rel="tooltip" title="Editar reporte"><i class="far fa-edit"></i> Editar</a>
            @endcan
        </div>
        <div class="lead">
            {{$reporte->description}}
        </div>
    </div>

{!! Form::open(['method' => 'POST', 'route' => ['reportes.cedula',  $reporte->id ], 'id' => 'frmReportecd1']) !!}
<div class="text-center mb-3">
    <div class="dropdown">
        <button type="submit" name="btn1" value="1" class="btn btn-outline-primary btn-sm"><i class="far fa-eye"></i> Cédula 1</button>
        <button type="submit" name="btn2" value="2" class="btn btn-outline-primary btn-sm"><i class="far fa-eye"></i> Cédula 2</button>
        <button class="btn btn-outline-primary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="far fa-eye"></i> Cédula 3
        </button>
        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
            <button type="submit" class="btn btn-outline-primary btn-sm dropdown-item"  name="btn3" value="3">
             Bellas artes
            </button>
            <button type="submit" class="btn btn-outline-primary btn-sm dropdown-item" name="btn4" value="4">
             Artes decorativas
            </button>
        </div>
    </div>
</div>
{!! $dataTable->table()  !!}
{!! $dataTable->scripts() !!}

{{--
    <table id="data-table-report" class="table table-striped table-bordered table-hover dt-responsive" style="width:100%">
        <thead>
            @foreach ($columnas as $modulo => $campos)
                @foreach ($campos as $idx => $campo)
                    @if (in_array($idx, $columnasSeleccionadas))
                        <th>{{$campo}}</th>
                    @endif
                @endforeach
            @endforeach
            <th>
                <div class="custom-control custom-checkbox">
                    {{ Form::checkbox('selectAll', null, false, [
                            'class' => 'custom-control-input',
                            'id' => 'selectAll',
                        ]) }}
                    <label class="custom-control-label" for="selectAll"></label>
                </div>
            </th>
        </thead>
        <tbody>
            @foreach ($piezas as $pieza)
                <tr>
                @foreach ($columnasSeleccionadas as $columnaSeleccionada)
                    @php
                        if(strpos($columnaSeleccionada, ".") !== false){
                            $separators = substr_count($columnaSeleccionada, ".");
                            switch ($separators) {
                                case 2:
                                    list($table1, $table2, $field) = explode(".", $columnaSeleccionada);
                                    $valor = isset($pieza->$table1->$table2->$field) ? $pieza->$table1->$table2->$field : '';
                                    break;
                                case 1:
                                    list($table, $field) = explode(".", $columnaSeleccionada);
                                    if($field == 'authors'){
                                        $valor = isset($pieza->$table->$field) ? implode(',', $pieza->$table->$field) : '';
                                    } else{
                                        $valor = isset($pieza->$table->$field) ? $pieza->$table->$field : '';
                                    }
                                    break;
                            }
                        } else{
                            $valor = $pieza->$columnaSeleccionada;
                        }
                    @endphp
                    <td>
                        @if (in_array($columnaSeleccionada, array('photo_inventory', 'photo_research')))
                            @isset($pieza->photography)
                                @php
                                    $aux = 0;
                                    list($foo, $type) = explode("_", $columnaSeleccionada);
                                @endphp
                                @foreach ($pieza->photography as $photography)
                                    @continue(($type == 'inventory' && $photography['module_id'] != 1) || ($type == 'research' && $photography['module_id'] != 2))
                                    <a href="{!! Storage::url('') !!}{!! config('fileuploads.'. $type .'.photographs.originals') !!}/{{$photography['file_name']}}" class="{{ $aux++ == 0 ? '' : 'd-none' }}" data-toggle="lightbox" data-gallery="gallery-{{$photography['piece_id']}}" data-title="{{$photography['photographer']}}" data-footer="{{$photography['description']}}">
                                        <img src="{!! Storage::url('') !!}{!! config('fileuploads.'. $type .'.photographs.thumbnails') !!}/{{$photography['file_name']}}" class="img-thumbnail" alt="{{$photography['description']}}">
                                    </a>
                                @endforeach
                            @endisset
                        @else
                            @switch($columnaSeleccionada)
                                @case('appraisal')
                                    {{money($valor)}}
                                    @break
                                @case('research.firm')
                                    {!! $valor ? "Sí" : "No" !!}
                                    @break
                                @case('tags2')
                                    {!! isset($valor) ? implode(',', $valor) : '' !!}
                                    @break
                                @default
                                    {!! nl2br($valor) !!}
                            @endswitch
                        @endif
                    </td>
                @endforeach
                    <td>
                        <div class="custom-control custom-checkbox">
                            {{ Form::checkbox('check_p[]', $pieza->id, false, [
                                    'class' => 'custom-control-input p_id',
                                    'id' => 'id_'.$pieza->id,
                                ]) }}
                            <label class="custom-control-label" for="id_{{$pieza->id}}"></label>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table> --}}

{!! Form::close() !!}
    @include('flash-toastr::message')
@endsection
