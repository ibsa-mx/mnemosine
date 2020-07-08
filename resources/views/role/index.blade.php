@extends('admin.layout')

@section('title', '- Roles y permisos')

@section('breadcrumb')
    <li class="breadcrumb-item">
        Roles de usuario
    </li>
@endsection

@section('content')
    <!-- Modal -->
    <div class="modal fade" id="roleModal" tabindex="-1" role="dialog" aria-labelledby="roleModalLabel">
        <div class="modal-dialog" role="document">
            {!! Form::open(['method' => 'post']) !!}
            <div class="modal-content">
                <div class="modal-header alert-dark">
                    <h5 class="modal-title" id="roleModalLabel">Nuevo rol</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- name Form Input -->
                    <div class="form-group @if ($errors->has('name')) has-error @endif">
                        {!! Form::hidden('guard_name', 'web') !!}
                        {!! Form::label('name', 'Nombre') !!}
                        {!! Form::text('name', null, ['class' => 'form-control', 'placeholder' => 'Escriba el nombre del rol']) !!}
                        @if ($errors->has('name')) <p class="help-block">{{ $errors->first('name') }}</p> @endif
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <!-- Submit Form Button -->
                    {!! Form::submit('Crear', ['class' => 'btn btn-primary']) !!}
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
    <div class="page-action text-right">
        @can('agregar_roles')
            <a href="#" class="btn btn-outline-primary btn-sm pull-right" data-toggle="modal" rel="tooltip" title="Nuevo rol" data-target="#roleModal" data-whatever="@mdo"><i class="fas fa-plus"></i> Agregar</a>
        @endcan
    </div>
    @forelse ($roles as $role)
        {!! Form::model($role, ['method' => 'PUT', 'route' => ['roles.update',  $role->id ], 'class' => 'm-b my-4']) !!}

        @if($role->name === 'Administrador')
            @include('shared._permissions', [
                          'title' => 'Permisos de '.$role->name,
                          'role' => $role,
                          'disabled' => true ])
        @else
            @include('shared._permissions', [
                          'title' => 'Permisos de '.$role->name,
                          'role' => $role,
                          'disabled' => false ])
        @endif
        {!! Form::close() !!}

    @empty
        <div class="alert alert-danger mt-3">No se han definido roles.</div>
    @endforelse
    @include('flash-toastr::message')
    <script type="text/javascript">
        $(function() {
            $("input[name='permissions[]']").on('change', function(event) {
                var modulo = $(this).data('modulo');
                var id = $(this).data('id');
                if(this.checked && $(this).data('accion') != 'ver'){
                    // si selecciona cualquier opcion se marca automaticamente el permiso para ver
                    $('#ver-' + modulo + '-' + id).prop('checked', true);
                }
                if(!this.checked && $(this).data('accion') == 'ver'){
                    // si quita el permiso para ver, los demas se deshabilitan
                    $('#agregar-' + modulo + '-' + id).prop('checked', false);
                    $('#editar-' + modulo + '-' + id).prop('checked', false);
                    $('#eliminar-' + modulo + '-' + id).prop('checked', false);
                }
                // si selecciona todos se marca la casilla del grupo
                if($('#ver-' + modulo + '-' + id).prop('checked') && $('#agregar-' + modulo + '-' + id).prop('checked') && $('#editar-' + modulo + '-' + id).prop('checked') && $('#eliminar-' + modulo + '-' + id).prop('checked')){
                    $('#' + modulo + '-' + id).prop('checked', true);
                } else{
                    $('#' + modulo + '-' + id).prop('checked', false);
                }
            });

            $(".switch-group").on('change', function(event) {
                var modulo = $(this).data('modulo');
                var id = $(this).data('id');
                $('#ver-' + modulo + '-' + id).prop('checked', this.checked);
                $('#agregar-' + modulo + '-' + id).prop('checked', this.checked);
                $('#editar-' + modulo + '-' + id).prop('checked', this.checked);
                $('#eliminar-' + modulo + '-' + id).prop('checked', this.checked);
            });

            // cambiar el estatus del check del grupo
            $(".switch-group").each(function(index) {
                var modulo = $(this).data('modulo');
                var id = $(this).data('id');
                if($('#ver-' + modulo + '-' + id).prop('checked') && $('#agregar-' + modulo + '-' + id).prop('checked') && $('#editar-' + modulo + '-' + id).prop('checked') && $('#eliminar-' + modulo + '-' + id).prop('checked')){
                    $('#' + modulo + '-' + id).prop('checked', true);
                } else{
                    $('#' + modulo + '-' + id).prop('checked', false);
                }
            });
        });
    </script>
@endsection
