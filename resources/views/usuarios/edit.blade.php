@extends('admin.layout')

@section('title', '- Editando al usuario ' . $user->name)

@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{ route('usuarios.index') }}">Usuarios</a>
    </li>
    <li class="breadcrumb-item active">
        Editando al usuario {{ $user->name }}
    </li>
@endsection

@section('content')
    <div class="container">
        {!! Form::model($user, ['method' => 'PUT', 'route' => ['usuarios.update',  $user->id ] ]) !!}
            @include('usuarios._form')

            <div class="text-center mb-3">
                <a href="{{ route('usuarios.index') }}" class="btn btn-secondary">Cancelar</a>
                {!! Form::submit('Guardar cambios', ['class' => 'btn btn-primary']) !!}
            </div>
        {!! Form::close() !!}
    </div>
    <script type="text/javascript">
    $(function() {
        // controlar el comportamiento de los switchs
        $("input[name='permissions[]']").on('change', function(event) {
            var modulo = $(this).data('modulo');
            if(this.checked && $(this).data('accion') != 'ver'){
                // si selecciona cualquier opcion se marca automaticamente el permiso para ver
                $('#ver-' + modulo).prop('checked', true);
            }
            if(!this.checked && $(this).data('accion') == 'ver'){
                // si quita el permiso para ver, los demas se deshabilitan
                $('#agregar-' + modulo).prop('checked', false);
                $('#editar-' + modulo).prop('checked', false);
                $('#eliminar-' + modulo).prop('checked', false);
            }
            // si selecciona todos se marca la casilla del grupo
            if($('#ver-' + modulo).prop('checked') && $('#agregar-' + modulo).prop('checked') && $('#editar-' + modulo).prop('checked') && $('#eliminar-' + modulo).prop('checked')){
                $('#' + modulo).prop('checked', true);
            } else{
                $('#' + modulo).prop('checked', false);
            }
        });

        $(".switch-group").on('change', function(event) {
            var modulo = $(this).data('modulo');
            $('#ver-' + modulo).prop('checked', this.checked);
            $('#agregar-' + modulo).prop('checked', this.checked);
            $('#editar-' + modulo).prop('checked', this.checked);
            $('#eliminar-' + modulo).prop('checked', this.checked);
        });

        // cambiar el estatus del check del grupo
        $(".switch-group").each(function(index) {
            var modulo = $(this).data('modulo');
            if($('#ver-' + modulo).prop('checked') && $('#agregar-' + modulo).prop('checked') && $('#editar-' + modulo).prop('checked') && $('#eliminar-' + modulo).prop('checked')){
                $('#' + modulo).prop('checked', true);
            } else{
                $('#' + modulo).prop('checked', false);
            }
        });
    });
    </script>
@endsection
