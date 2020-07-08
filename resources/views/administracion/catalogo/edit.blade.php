@extends('admin.layout')


@section('title', '- Administración-Catálogo')

@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{route('catalogos.index')}}">Catálogos</a>
    </li>
    <li class="breadcrumb-item active">
        Editando catálogo "<em>{{$catalogo->title}}</em>"
    </li>
@endsection

@section('content')
<script type="text/javascript">
    $(function() {
        $('#catalogomodal').modal('show');
    });
</script>

<div class="modal fade" id="catalogomodal" tabindex="-1" role="dialog" aria-labelledby="catalogomodalLabel" aria-hidden="true">
    <div class="modal-dialog modal-primary" role="document">
        <div class="modal-content">
            {!! Form::model($catalogo, ['method' => 'PUT', 'route' => ['catalogos.update',  $catalogo->id ] ]) !!}
            <div class="modal-header alert-dark">
                <h5 class="modal-title" id="catalogomodalLabel">Editar Catalogo</h5>
                <a href="{{route('catalogos.index')}}" class="close">  <span aria-hidden="true">&times;</span></a>
            </div>
            <div class="modal-body">
                    <div class="form-group">
                        <label for="catalogtitle"  class="col-form-label">Nombre</label>
                        <input type="text" class="form-control @error('catalogtitle') is-invalid @enderror" name="catalogtitle" id="catalogtitle" placeholder="Nombre del catálogo" value="{{$catalogo->title}}" >
                        </div>
                        @error('catalogtitle')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                        <div class="form-group">
                            <label for="catalogdescription"  class="col-form-label">Descripción</label>
                            <textarea class="form-control @error('catalogdescription') is-invalid @enderror" name="catalogdescription" id="catalogdescription" placeholder="Escribe una breve descripción">{{$catalogo->description}}</textarea>
                        </div>
                        @error('catalogdescription')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                </div>
                <div class="modal-footer">
                    <a href="{{route('catalogos.index')}}" class="btn btn-secondary">Cancelar</a>
                    <input type="submit" class="btn btn-primary" value="Editar">
                </div>
                </form>
            </div>
        </div>
    </div>
@endsection
