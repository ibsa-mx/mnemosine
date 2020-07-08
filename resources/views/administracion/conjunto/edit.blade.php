@extends('admin.layout')


@section('title', '- Administración')

@section('breadcrumb')
    <li class="breadcrumb-item">
        Conjuntos
    </li>
@endsection

@section('content')
<script type="text/javascript">
  $(function() {
    $('#conjuntomodal').modal('show');
  });
</script>

<!--Modal para editar un conjunto -->
<div class="modal fade" id="conjuntomodal" tabindex="-1" role="dialog" aria-labelledby="conjuntomodalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header alert-dark">
        <h5 class="modal-title" id="conjuntomodalLabel">Editar Conjunto</h5>
        <a href="/conjuntos" class="close">  <span aria-hidden="true">&times;</span></a>
      </div>
      <div class="modal-body">
        <form class="form-group" method="POST" action="/conjuntos/{{$set->id}}">
          @csrf
          @method('PUT')
          <div class="form-group">
            <label for="settitle"  class="col-form-label">Nombre</label>
            <input type="text" class="form-control @error('settitle') is-invalid @enderror" name="settitle" id="settitle" value="{{$set->title}}" placeholder="Nombre del conjunto" >
          </div>
          @error('settitle')
			<div class="alert alert-danger">{{ $message }}</div>
		  @enderror
          <div class="form-group">
            <label for="description"  class="col-form-label">Descripción</label>
            <textarea class="form-control @error('setdescription') is-invalid @enderror" name="setdescription" id="setdescription" placeholder="Escribe una breve descripción">{{$set->description}}</textarea>
          </div>
          @error('setdescription')
			<div class="alert alert-danger">{{ $message }}</div>
		  @enderror
          <div class="modal-footer">
            <input type="submit" class="btn btn-primary" name="Guardar">
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
