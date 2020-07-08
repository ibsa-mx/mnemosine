@extends('admin.layout')

@section('title', '- Consultas, ver pieza')

@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{route('consultas')}}">Consultas</a>
    </li>
    <li class="breadcrumb-item active">
        Información de la pieza {{$piece->inventory_number}}
    </li>
@endsection

@push('after_all_styles')
    <link href="{{asset('admin/vendors/lightbox-master/dist/ekko-lightbox.css')}}" rel="stylesheet">
    <link href="{{asset('admin/css/show.css')}}" rel="stylesheet">
@endpush

@push('after_all_scripts')
    <script src="{{asset('admin/vendors/lightbox-master/dist/ekko-lightbox.min.js')}}"></script>
    <script src="{{asset('admin/vendors/clipboard.js/dist/clipboard.min.js')}}"></script>
    <script type="text/javascript">
    $(function() {
        if(ClipboardJS.isSupported()){
            var clipboard = new ClipboardJS('.clipboard');
            clipboard.on('success', function(e) {
                $(e.trigger).tooltip('hide');
                toastr.info('¡Se ha copiado el texto al portapapeles!');
                e.clearSelection();
            });
        } else{
            $(".clipboard").addClass("d-none");
        }

        $("#descargarXML").on('click', function(e){
            window.location.href = "{{route('consultas.xml', $piece->id)}}";
        });

        $("#descargarWORD").on('click', function(e){
            window.location.href = "{{route('consultas.word', $piece->id)}}";
        });


        $("#descargarEXCEL").on('click', function(e){
            window.location.href = "{{route('consultas.excel', $piece->id)}}";
        });

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

@push('metadata')
    @isset($research)
        <meta name="DC.Title" content="{{$research->title}}"/>
        @isset($authorNames)
            @foreach ($authorNames as $authorName)
                <meta name="author" content="{{$authorName}}"/>
                <meta name="DC.Creator" content="{{$authorName}}"/>
            @endforeach
        @endisset
        <meta name="DC.Date" content="{{$research->creation_date}}"/>
    @endisset
    <meta name="DC.Description" content="{{$piece->description_origin}}"/>
    <meta name="DC.Language" content="es"/>
    <meta name="DC.Format.Width" content="{{$piece->width}}"/>
    <meta name="DC.Format.Height" content="{{$piece->height}}"/>
    <meta name="DC.Identifier" content="{{$piece->origin_number}}"/>
    <meta name="DC.Identifier" content="{{$piece->inventory_number}}"/>
    <meta name="DC.Identifier" content="{{$piece->catalog_number}}"/>
    <meta name="DC.Type" content="Illustration"/>
    <meta name="DC.Publisher" content="{{config('app.institution')}}"/>
    <meta name="DC.Subject" content="{{ isset($gender->title) ? $gender->title : ''}}"/>
    <meta name="DC.Subject" content="{{ isset($subgender->title) ? $subgender->title : ''}}"/>
@endpush

@section('content')
<div class="row">
    <div class="col-md-10 px-1">
        @include('consulta._inventario')
        @include('consulta._investigacion')
        @include('consulta._restauracion')
        @include('consulta._movimientos')
    </div>
    <div class="col-md-2 px-1">
        <div class="text-center mb-3">
            <div class="btn-group" role="group" aria-label="Formatos de descarga">
                <button class="btn btn-outline-primary" id="descargarWORD" data-toggle="tooltip" title="Descargar WORD">
                    <i class="fas fa-2x fa-file-word"></i>
                </button>
                <button class="btn btn-outline-primary" id="descargarEXCEL" data-toggle="tooltip" title="Descargar EXCEL">
                    <i class="fas fa-2x fa-file-excel"></i>
                </button>
                <button class="btn btn-outline-primary" id="descargarXML" data-toggle="tooltip" title="Descargar XML">
                     <i class="fas fa-2x fa-file-code"></i>
                </button>
            </div>


        </div>
        @include('consulta._images', [
            'module' => 'Inventario',
            'label' => 'inventario',
            'conf' => 'inventory',
        ])
        @include('consulta._images', [
            'module' => 'Investigacion',
            'label' => 'investigación',
            'conf' => 'research',
        ])
        @include('consulta._images', [
            'module' => 'Restauracion',
            'label' => 'restauración',
            'conf' => 'restoration',
        ])
    </div>
</div>

<div class="modal fade" id="appraisalModal" tabindex="-1" role="dialog" aria-labelledby="appraisalModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-primary" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="appraisalModalLabel">Historial de avalúo</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                @if(isset($appraisals) && $appraisals->count() > 0)
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th scope="col">Avalúo</th>
                                <th scope="col">Modificado por</th>
                                <th scope="col">Fecha</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($appraisals as $idx => $appraisal)
                                <tr>
                                    <th scope="row">{{ money($appraisal->appraisal) }} USD</th>
                                    <td>{{$appraisal->update_name}} &lt;{{$appraisal->update_email}}&gt;</td>
                                    <td><span rel="tooltip" title="{{ $appraisal->created_at->locale('es_MX')->isoFormat('LL') }}">{{$appraisal->created_at->diffForHumans()}}</span></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="alert alert-info">No hay historial de avaluo</div>
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
@endsection
