@extends('admin.layout')

@section('title', '- Catálogos')

@section('breadcrumb')
    <li class="breadcrumb-item active">
        Catálogos
    </li>
@endsection

@section('content')
    @can('agregar_catalogos')
        <div class="row flex-row-reverse m-2">
            <button type="button" class="btn btn-outline-primary btn-sm" data-toggle="modal" data-target="#catalogomodal" rel="tooltip" data-html="true" title="Nuevo catálogo" data-whatever="@mdo"><i class="fas fa-plus"></i> Agregar</button>
        </div>
    @endcan
    <div >
        <table id="data-table" class="table table-striped table-bordered table-hover dt-responsive" style="width:100%">
            <thead>
                <th>Nombre</th>
                <th>Descripción</th>
                @canany(['ver_catalogos', 'editar_catalogos', 'eliminar_catalogos'])
                    <th>Acciones</th>
                @endcanany
            </thead>
            <tbody>
                @foreach($catalogs as $catalog)
                    <tr>
                        <td>{{$catalog->title}}</td>
                        <td>{{$catalog->description}}</td>
                        <td class="text-center">
                            @can('ver_catalogos')
                                <a href="{{route('catalogoElementos.show', $catalog->id)}}" class="btn btn-sm btn-outline-primary" data-toggle="tooltip" title="Ver elementos"><i class="far fa-eye"></i></a>
                            @endcan
                            {{-- @can('editar_catalogos')
                                <a href="{{route('catalogos.edit', $catalog->id)}}" class="btn btn-sm btn-outline-primary" data-toggle="tooltip" title="Editar catálogo"><i class="far fa-edit"></i></a>
                            @endcan --}}
                            @can('eliminar_catalogos')
                                {!! Form::open( ['method' => 'delete', 'url' => route('catalogos.destroy', $catalog->id), 'style' => 'display: inline', 'onSubmit' => 'return confirm("¿Realmente desea eliminar el elemento?")']) !!}
                                    <button type="submit" class="btn-delete btn btn-sm btn-outline-danger" rel="tooltip" title="Eliminar catálogo">
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


    <!--Modal para agregar un conjunto -->
    <div class="modal fade" id="catalogomodal" tabindex="-1" role="dialog" aria-labelledby="catalogomodalLabel" aria-hidden="true">
        <div class="modal-dialog modal-primary" role="document">
            <div class="modal-content">
                {!! Form::open(['route' => 'catalogos.store', 'id' => 'frmNuevo']) !!}
                <div class="modal-header">
                    <h5 class="modal-title" id="catalogomodalLabel">Nuevo catálogo</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="catalogtitle" class="col-form-label">
                            Nombre
                        </label>
                        <input type="text" class="form-control @error('catalogtitle') has-error @enderror" name="catalogtitle" id="gendertitle" placeholder="Nombre del catálogo"/>
                    </div>
                    @error('catalogtitle')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                    <div class="form-group">
                        <label for="description"  class="col-form-label">Descripción</label>
                        <textarea class="form-control @error('catalogdescription') has-error @enderror" name="catalogdescription" id="catalogdescription" placeholder="Escribe una breve descripción"></textarea>
                    </div>
                    @error('catalogdescription')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="modal-footer">
                    <input type="submit" class="btn btn-primary" name="Crear">
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
@include('flash-toastr::message')
@endsection
