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
    <script src="{{ asset('admin/js/reportesShow.js?20200130') }}"></script>
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
    <div class="bg-primary text-white px-3 py-1">
        <div class="h3">
            {{$reporte->name}}
            @can('editar_reportes')
                <a href="{{ route('reportes.edit', ['id' => $reporte->id]) }}" class="mt-1 btn btn-sm btn-dark float-right" rel="tooltip" title="Editar reporte"><i class="far fa-edit"></i> Editar</a>
            @endcan
        </div>
        <div class="lead mb-2">
            {{$reporte->description}}
        </div>
        @if ($reporte->lending_list)
            <div>
                Institución: {{$reporte->institution}}
                <br />
                Nombre de la exposición: {{$reporte->exhibition}}
                <br/>
                Fechas de exhibición: {{$reporte->exhibition_date_ini->locale('es_MX')->isoFormat('LL')}} al
                    {{$reporte->exhibition_date_fin->locale('es_MX')->isoFormat('LL')}}
            </div>
        @endif
    </div>
    <div class="text-right mb-3">
        <small class="text-muted">
            Reporte creado por {{$reporte->creator->name}} el {{$reporte->created_at->locale('es_MX')->isoFormat('LLL')}},
            modificado por {{$reporte->updater->name ?? $reporte->creator->name}} el {{$reporte->updated_at->locale('es_MX')->isoFormat('LLL')}}
        </small>
    </div>

    @if (count($errors) > 0)
        <div class="alert alert-danger">
            Hubo problemas con los datos ingresados.<br/><br/>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

{!! Form::open(['method' => 'POST', 'route' => ['reportes.cedula',  $reporte->id ], 'id' => 'frmReportecd1']) !!}
@hasanyrole('Investigador|Administrador|Investigación|Investigación Servicio')
<div class="card card-accent-info mb-3">
    <div class="card-header h5 p-2 bg-gray-200" role="tab">
        <a href="#" data-toggle="collapse" data-target="#collapseInvestigacion">
            Opciones de Investigación
        </a>
        <div class="card-header-actions">
            <a class="card-header-action btn-minimize" href="#" data-toggle="collapse" data-target="#collapseInvestigacion" aria-expanded="true" rel="tooltip" title="Mostrar u ocultar">
                <i class="icon-arrow-up"></i>
            </a>
        </div>
    </div>
    <div id="collapseInvestigacion" class="collapse show text-center p-3 bg-gray-100">
        <div class="dropdown">
            <button type="submit" name="btn_cedula" value="1" class="btn btn-outline-primary btn-sm"><i class="far fa-eye"></i> Cédula 1</button>
            <button type="submit" name="btn_cedula" value="2" class="btn btn-outline-primary btn-sm"><i class="far fa-eye"></i> Cédula 2</button>
            <button class="btn btn-outline-primary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="far fa-eye"></i> Cédula 3
            </button>
            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                <button type="submit" class="btn btn-outline-primary btn-sm dropdown-item"  name="btn_cedula" value="3a">
                 Bellas artes
                </button>
                <button type="submit" class="btn btn-outline-primary btn-sm dropdown-item" name="btn_cedula" value="3b">
                 Artes decorativas
                </button>
            </div>
            <button type="submit" name="btn_cedula" value="4" class="btn btn-outline-primary btn-sm"><i class="far fa-eye"></i> Cédula 4</button>
        </div>

        <div class="mb-3 text-left">
            <strong>Piezas para generar la cédula</strong>
            <select class="select2 form-control @error('pieces_ids') is-invalid @enderror" name="pieces_ids[]" id="piezas_id" multiple="multiple">
            </select>
            @error('pieces_ids')
                <div class="invalid-feedback">
                    {{ $errors->first('pieces_ids') }}
                </div>
            @enderror
        </div>

        <div class="row justify-content-center">
            <div class="col-md-4">
                <button type="button" id="btn-cargar-piezas" class="btn btn-primary btn-block"><i class="fas fa-chevron-up"></i> Agregar piezas</button>
            </div>
        </div>
    </div>
</div>
@endhasanyrole
    {!! $dataTable->table()  !!}
    {!! $dataTable->scripts() !!}
{!! Form::close() !!}
    @include('flash-toastr::message')
@endsection
