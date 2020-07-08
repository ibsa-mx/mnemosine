@extends('admin.layout')

@section('title', '- Restauración, editar datos')

@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{route('restauracion.index')}}">Restauración</a>
    </li>
    <li class="breadcrumb-item">
        <a href="{{route('restauracion.listRecords', ['pieceId' => $piece->id])}}">Historial de restauración</a>
    </li>
    <li class="breadcrumb-item active">
        Editando restauración para la pieza {{$piece->inventory_number}}
    </li>
@endsection

@section('content')
    {!! Form::model($restoration, ['method' => 'PUT', 'route' => ['restauracion.update',  $restoration->id ], 'files' => true, 'id' => 'frmRestauracion']) !!}
        @include('restauracion._form')
        <div class="text-center mb-3">
            <a href="{{ route('restauracion.listRecords', ['pieceId' => $piece->id]) }}" class="btn btn-secondary">Cancelar</a>
            {!! Form::submit('Guardar cambios', ['class' => 'btn btn-primary']) !!}
        </div>
    {!! Form::close() !!}
@endsection


@push('after_jquery')
<script type="text/javascript">
@isset($photographs)
    // info para las fotos
    var urlStoragePhotographs = '{!! Storage::url('') !!}{!! config('fileuploads.restoration.photographs.originals') !!}/';
    var photosBD = JSON.parse('{!! json_encode($photographs->groupBy('id')) !!}');
@endisset
@isset($documents)
    // info para los documentos
    var urlStorageDocuments = '{!! Storage::url('') !!}{!! config('fileuploads.restoration.documents.originals') !!}/';
    var documentsBD = JSON.parse('{!! json_encode($documents->groupBy('id')) !!}');
@endisset
</script>
@endpush
