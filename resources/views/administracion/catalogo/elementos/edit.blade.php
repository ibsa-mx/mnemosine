@extends('admin.layout')

@section('title', '- Editando elemento de catálogo')

@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{route('catalogos.index')}}">Catálogos</a>
    </li>
    <li class="breadcrumb-item">
        <a href="{{route('catalogoElementos.show', $element->catalog_id)}}">Elementos del catálogo</a>
    </li>
    <li class="breadcrumb-item active">
        Editando elemento de catálogo "<em>{{$element->title}}</em>"
    </li>
@endsection

@section('content')
    <script type="text/javascript">
    $(function() {
        $('#catalogoElementomodal').modal('show');
    });
</script>

<!--Modal para editar un conjunto -->
<div class="modal fade" id="catalogoElementomodal" tabindex="-1" role="dialog" aria-labelledby="catalogoElementomodalLabel" aria-hidden="true">
    <div class="modal-dialog modal-primary" role="document">
        <div class="modal-content">
            {!! Form::model($element, ['method' => 'PUT', 'route' => ['catalogoElementos.update',  $element->id ] ]) !!}
                <div class="modal-header alert-dark">
                    <h5 class="modal-title" id="catalogoElementomodalLabel">Editar elemento del catálogo</h5>
                    <a href="{{route('catalogoElementos.show', $element->catalog_id)}}" class="close"><span aria-hidden="true">&times;</span></a>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="elementtitle"  class="col-form-label">Nombre</label>
                        <input type="text" class="form-control @error('elementtitle') has-error @enderror" name="elementtitle" id="elementtitle" placeholder="Nombre del elemento" value="{{$element->title}}" >
                        </div>
                        @error('elementtitle')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                        {{-- <div class="form-group">
                            <label for="elementcode"  class="col-form-label">Código</label>
                            <input type="text" class="form-control" name="elementcode" id="elementcode" placeholder="Código del elemento" value="{{$element->code}}" >
                        </div> --}}
                        <div class="form-group">
                            <label for="elementdescription"  class="col-form-label">Descripción</label>
                            <textarea class="form-control @error('elementdescription') has-error @enderror" name="elementdescription" id="elementdescription" placeholder="Escribe una breve descripción">{{$element->description}}</textarea>
                            </div>
                            @error('elementdescription')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="modal-footer">
                            <a href="{{route('catalogoElementos.show', $element->catalog_id)}}" class="btn btn-secondary">Cancelar</a>
                            <input type="submit" class="btn btn-primary" value="Modificar">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endsection
