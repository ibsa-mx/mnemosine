@extends('admin.layout')

@section('title', '- Administración')

@section('breadcrumb')
    <li class="breadcrumb-item">
        Conjuntos
    </li>
@endsection

@section('content')
 	<label for="nombre" class="d-flex flex-row bd-highlight"><h6>Conjuntos</h6></label>

    <div class="row flex-row-reverse m-2">
        <button type="button" class="btn btn-outline-primary btn-sm" data-toggle="modal" data-target="#conjuntomodal" rel="tooltip" data-html="true" title="Nuevo Conjunto" data-whatever="@mdo"><i class="fas fa-plus"></i></button>
    </div>

    <div>
      <table id="example" class="display" style="width:100%">
        <thead class="thead-light">
          <th>Nombre</th>
          <th>Descripción</th>
          <th>Editar</th>
          <th>Eliminar</th>
        </thead>
        <tbody>
	        @foreach($sets as $set)
	        	<tr>
	        		<td>{{$set->title}}</td>
	        		<td>{{$set->description}}</td>
	        		<td><a href="/conjuntos/{{$set->id}}/edit" data-toggle="tooltip" title="Editar conjunto"><i class="fas fa-edit"></i></a></td>
	        		<td><a href="/conjuntos/{{$set->id}}/delete" data-toggle="tooltip" title="Eliminar conjunto"><i class="fas fa-trash-alt"></i></a></td>
	        	</tr>
	        @endforeach
        </tbody>
      </table>
    </div>


<!--Modal para agregar un conjunto -->
<div class="modal fade" id="conjuntomodal" tabindex="-1" role="dialog" aria-labelledby="conjuntomodalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header alert-dark">
        <h5 class="modal-title" id="conjuntomodalLabel">Nuevo Conjunto</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form class="form-group" method="POST" action="/conjuntos_add">
          @csrf
          <div class="form-group">
            <label for="settitle"  class="col-form-label">Nombre</label>
            <input type="text" class="form-control @error('settitle') is-invalid @enderror" name="settitle" id="settitle" placeholder="Nombre del conjunto" valor = "" >
          </div>
          @error('settitle')
			<div class="alert alert-danger">{{ $message }}</div>
		  @enderror
          <div class="form-group">
            <label for="description"  class="col-form-label">Descripción</label>
            <textarea class="form-control @error('settitle') is-invalid @enderror" name="setdescription" id="setdescription" placeholder="Escribe una breve descripción" valor = "" ></textarea>
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

<script>
	$(function() {
	    setTimeout(function() {
	        $(".alert").alert('close');
	    }, 2000);
	});
</script>
