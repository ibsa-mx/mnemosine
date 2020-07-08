@extends('admin.layout')

@section('title', '- Restauracion')

@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{route('restauracion.index')}}">Restauración</a>
    </li>
    <li class="breadcrumb-item active">
        Historial de restauración de la pieza {{$piece->inventory_number}}
    </li>
@endsection

@push('after_all_styles')
    <link href="{{asset('admin/vendors/lightbox-master/dist/ekko-lightbox.css')}}" rel="stylesheet">
    <link href="{{asset('admin/css/show.css')}}" rel="stylesheet">
@endpush

@push('after_all_scripts')
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

@section('content')
    @can('agregar_restauracion')
        <div class="page-action text-right mb-3">
            <a href="{{ route('restauracion.create', ['pieceId' => $piece->id]) }}" class="btn btn-outline-primary btn-sm" rel="tooltip" title="Agregar restauración"><i class="fas fa-plus"></i> Agregar</a>
        </div>
    @endcan
    <div class="alert alert-info">
        <div class="row">
            <div class="col">
                <span class="label">No. procedencia:</span>
                {{$piece->origin_number}}
            </div>
            <div class="col">
                <span class="label">No. inventario:</span>
                {{$piece->inventory_number}}
            </div>
            <div class="col">
                <span class="label">No. catálogo:</span>
                {{$piece->catalog_number}}
            </div>
        </div>
    </div>
    <table id="data-table" class="table table-striped table-bordered table-hover dt-responsive">
        <thead>
            <tr>
                <th>Fecha de tratamiento</th>
                <th>Exámen preliminar</th>
                <th>Fotografía</th>
                @canany(['editar_restauracion', 'eliminar_restauracion'])
                    <th>Acciones</th>
                @endcanany
            </tr>
        </thead>
        <tbody>
            @foreach($restorations as $restoration)
                <tr>
                    <td data-order="{{$restoration->treatment_date->timestamp}}">{{$restoration->treatment_date->format('d/m/Y')}}</td>
                    <td>{{Str::limit($restoration->preliminary_examination, 170)}}</td>
                    <td class="text-center align-middle p-1">
                        @isset($photographs)
                            @php
                            $aux = 0;
                            $restorationPhotographsIds = explode(",", $restoration->photographs_ids);
                            @endphp
                            @foreach ($photographs as $photography)
                                @if (in_array($photography->id, $restorationPhotographsIds))
                                    <a href="{!! Storage::url('') !!}{!! config('fileuploads.restoration.photographs.originals') !!}/{{$photography->file_name}}" class="{{ $aux++ == 0 ? '' : 'd-none' }}" data-toggle="lightbox" data-gallery="gallery-{{$restoration->id}}" data-title="{{$photography->photographer}}" data-footer="{{$photography->description}}">
                                        <img src="{!! Storage::url('') !!}{!! config('fileuploads.restoration.photographs.thumbnails') !!}/{{$photography->file_name}}" class="img-thumbnail" alt="{{$photography->description}}">
                                    </a>
                                @endif
                            @endforeach
                        @endisset
                    </td>
                    @canany(['ver_restauracion', 'editar_restauracion', 'eliminar_restauracion'])
                        <td class="text-center">
                            @can('editar_restauracion')
                                <a href="{{ route('restauracion.edit', ['restauracion' => $restoration->id])  }}" class="btn btn-sm btn-outline-primary" rel="tooltip" title="Editar restauración"><i class="far fa-edit"></i></a>
                            @endcan
                            @can('editar_restauracion')
                                {!! Form::open( ['method' => 'delete', 'url' => route('restauracion.destroy', ['restauracion' => $restoration->id]), 'style' => 'display: inline', 'onSubmit' => 'return confirm("¿Realmente desea eliminar esta información?")']) !!}
                                <button type="submit" class="btn-delete btn btn-sm btn-outline-danger" rel="tooltip" title="Eliminar información de restauración">
                                    <i class="far fa-trash-alt"></i>
                                </button>
                                {!! Form::close() !!}
                            @endcan
                        </td>
                    @endcanany
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="text-center mt-3">
        <a href="{{route('restauracion.index')}}" class="btn btn-primary">Regresar al listado de piezas</a>
    </div>
    @include('flash-toastr::message')
@endsection
