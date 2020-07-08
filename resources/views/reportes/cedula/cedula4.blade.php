@extends('admin.layout')
@section('title', '- Ver reporte')

@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{route('reportes.index')}}">Reportes</a>
    </li>
    <li class="breadcrumb-item active">
        <a href="{{route('reportes.show', $id)}}">Ver reporte</a>
    </li>
    <li class="breadcrumb-item">
    	Cédula 4
    </li>
@endsection

@section('content')
<div class="text-center mb-3">
    <a href="{{route('reportes.descargarCedula', '4')}}" class="btn btn-outline-primary btn-sm"><i class="fas fa-file-word"></i> Exportar a Word</a>
</div>

@if($piezas != null)
    <table class="cedula4 table">
    	@foreach($piezas as $idx => $pieza)
            @continue(!isset($pieza->research))
                <tr>
                    <td>
                        {{$idx+1}}. <img src="{!! Storage::url('') !!}{!! config('fileuploads.inventory.photographs.thumbnails') !!}/{{$pieza->photography[0]->file_name}}" alt="{{$pieza->photography[0]->description}}"/>
                    </td>
                    <td>
                        @foreach (explode(",", $reporte->columns) as $idx => $column)
                            @php
                                $campo = explode(".", $column);
                                if(($column == "photo_inventory") || ($column == "photo_research")){
                                    continue;
                                }
                                switch(count($campo)){
                                    case 1:
                                        switch($column){
                                            case "measure_with":
                                                echo $pieza->height_with_base . " x " . $pieza->width_with_base . " x " . $pieza->depth_with_base . " ø " . $pieza->diameter_with_base . " cm";
                                                break;
                                            case "measure_without":
                                                echo $pieza->height . " x " . $pieza->width . " x " . $pieza->depth . " ø " . $pieza->diameter . " cm";
                                                break;
                                            case "appraisal":
                                                echo "$" . number_format($pieza->{$campo[0]}, 2);
                                                break;
                                            case "tags":
                                                echo implode(", ", $pieza->{$campo[0]});
                                                break;
                                            default:
                                                if(empty($pieza->{$campo[0]})) continue 3;
                                                echo $pieza->{$campo[0]};
                                        }
                                        break;
                                    case 2:
                                        if(empty($pieza->{$campo[0]}->{$campo[1]})) continue 2;
                                        if($column == "research.authors"){
                                            echo implode(", ", $pieza->{$campo[0]}->{$campo[1]});
                                        } else {
                                            echo $pieza->{$campo[0]}->{$campo[1]};
                                        }
                                        break;
                                    case 3:
                                        if(empty($pieza->{$campo[0]}->{$campo[1]}->{$campo[2]})) continue 2;
                                        echo $pieza->{$campo[0]}->{$campo[1]}->{$campo[2]};
                                        break;
                                }
                            @endphp
                            <br />
                        @endforeach
                    </td>
                    <td>
                        {{$pieza->research->card}}
                    </td>
                </tr>
    	@endforeach
    </table>
@endif

@endsection
