@extends('admin.layout')

@section('title', '- Elementos de catálogo')

@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="/catalogos">Catálogos</a>
    </li>
    <li class="breadcrumb-item active">
        Elementos del catálogo <em>"{{$catalog->title}}"</em>
    </li>
@endsection

@section('content')
    @can('agregar_catalogos')
        <div class="row flex-row-reverse m-2">
            <button type="button" class="btn btn-outline-primary btn-sm" data-toggle="modal" data-target="#elementmodal" rel="tooltip" data-html="true" title="Nuevo elemento del catálogo" data-whatever="@mdo"><i class="fas fa-plus"></i> Agregar</button>
        </div>
    @endcan
    <div>
        <table id="data-table" class="table table-striped table-bordered table-hover dt-responsive" style="width:100%">
            <thead>
                <th>Nombre</th>
                <th>Descripción</th>
                @canany(['ver_catalogos', 'editar_catalogos', 'eliminar_catalogos'])
                    <th>Acciones</th>
                @endcanany
            </thead>
            <tbody>
                @foreach($elements as $element)
                    <tr>
                        <td>{{$element->title}}</td>
                        <td>{{$element->description}}</td>
                        <td class="text-center">
                            @can('editar_catalogos')
                                <a href="{{route('catalogoElementos.edit', $element->id)}}" class="btn btn-sm btn-outline-primary" data-toggle="tooltip" title="Editar elemento del catálogo"><i class="far fa-edit"></i></a>
                            @endcan
                            @can('eliminar_catalogos')
                                {!! Form::open( ['method' => 'delete', 'url' => route('catalogoElementos.destroy', $element->id), 'style' => 'display: inline', 'onSubmit' => 'return confirm("¿Realmente desea eliminar el elemento?")']) !!}
                                    <button type="submit" class="btn-delete btn btn-sm btn-outline-danger" rel="tooltip" title="Eliminar elemento de catálogo">
                                        <i class="far fa-trash-alt"></i>
                                    </button>
                                {!! Form::close() !!}
                            @endcan
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!--Modal -->
    <div class="modal fade" id="elementmodal" tabindex="-1" role="dialog" aria-labelledby="catalogomodalLabel" aria-hidden="true">
        <div class="modal-dialog modal-primary" role="document">
            <div class="modal-content">
                {!! Form::open(['route' => 'catalogoElementos.store', 'id' => 'frmNuevo']) !!}
                    <div class="modal-header">
                        <h5 class="modal-title" id="catalogomodalLabel">Nuevo elemento del catálogo</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="elementtitle"  class="col-form-label">Nombre</label>
                            <input type="text" class="form-control @error('elementtitle') is-invalid @enderror" name="elementtitle" id="elementtitle" placeholder="Nombre del elemento del catálogo" valor = "" >
                            </div>
                            @error('elementtitle')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                            {{-- <div class="form-group">
                                <label for="elementcode"  class="col-form-label">Código</label>
                                <input type="hidden" class="form-control" name="elementcode" id="elementcode" placeholder="Código del elemento" valor = "" >
                            </div> --}}
                            <div class="form-group">
                                <label for="elementdescription"  class="col-form-label">Descripción</label>
                                <textarea class="form-control @error('elementdescription') is-invalid @enderror" name="elementdescription" id="elementdescription" placeholder="Escribe una breve descripción" valor = "" ></textarea>
                                </div>
                                @error('elementdescription')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                                <input type="hidden" name="catalogo" value="{{$catalog->id}}">
                            </div>
                            <div class="modal-footer">
                                <input type="submit" class="btn btn-primary" value="Crear">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
@include('flash-toastr::message')
@endsection
