<div class="card card-accent-info">
    <div class="card-header" role="tab" id="{{ isset($title) ? str_slug($title) :  'permissionHeading' }}">
        <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#dd-{{ isset($title) ? str_slug($title) :  'permissionHeading' }}" aria-expanded="true" aria-controls="collapseOne">
            <span class="h3 text-dark">{{ $title ?? 'Permisos' }} {!! isset($user) ? '<span class="text-danger">(' . $user->getDirectPermissions()->count() . ')</span>' : '' !!}</span>
        </button>
        @if (isset($role) && $role->name == 'Administrador')
            <span class="badge badge-info text-white" rel="tooltip" title="No se pueden modificar estos permisos">Informativo</span>
        @endif
        <div class="card-header-actions">
            {{-- <a class="card-header-action btn-setting" href="#">
                <i class="icon-settings"></i>
            </a> --}}
            <a class="card-header-action btn-minimize" href="#" data-toggle="collapse" data-target="#dd-{{ isset($title) ? str_slug($title) :  'permissionHeading' }}" aria-expanded="true" rel="tooltip" title="Mostrar u ocultar permisos">
                <i class="icon-arrow-up"></i>
            </a>
        </div>
    </div>
    <div id="dd-{{ isset($title) ? str_slug($title) :  'permissionHeading' }}" class="collapse{{ (isset($role) && $role->name == 'Administrador') ? '' : ' show' }}" aria-labelledby="{{ isset($title) ? str_slug($title) :  'permissionHeading' }}">
        <div class="card-body">
            <div class="row">
                @php
                    $moduloAnterior = "";
                @endphp
                @foreach($permissions as $perm)
                    @php
                        list($accion, $moduloActual) = explode("_", $perm->name);

                        $per_found = null;
                        if( isset($role) ) {
                            $per_found = $role->hasPermissionTo($perm->name);
                        }
                        if( isset($user)) {
                            $per_found = $user->hasDirectPermission($perm->name);
                        }
                        $optionsGroup = ['class' => 'switch-input switch-group', 'data-modulo' =>  $moduloActual, 'id' => $moduloActual];
                        $options = ['class' => 'switch-input', 'data-modulo' => $moduloActual, 'data-accion' => $accion, 'id' => $accion . '-' . $moduloActual];

                        if(isset($disabled) && $disabled){
                            $options['disabled'] = 'disabled';
                            $optionsGroup['disabled'] = 'disabled';
                        }
                        if(isset($role->id)){
                            $options['data-id'] = $role->id;
                            $optionsGroup['data-id'] = $role->id;
                            $options['id'] .= '-' . $role->id;
                            $optionsGroup['id'] .= '-' . $role->id;
                        }
                    @endphp
                    @if ($moduloActual != $moduloAnterior)
                        <div class="w-100 h5 pl-3 pt-2 text-info bg-light">
                            <span class="align-top">{{ Str::title($moduloActual) }}</span>
                            <label class="switch switch-label switch-pill switch-outline-success-alt align-bottom">
                                {!! Form::checkbox("", "", $per_found, $optionsGroup) !!}
                                <span class="switch-slider" data-checked="✓" data-unchecked="✕"></span>
                            </label>
                        </div>
                    @endif
                    <div class="col">
                        <div class="checkbox">
                            <label class="switch switch-label switch-pill switch-outline-{{ $accion == 'eliminar' ? 'danger' : 'info' }}-alt">
                                {!! Form::checkbox("permissions[]", $perm->name, $per_found, $options) !!}
                                <span class="switch-slider" data-checked="✓" data-unchecked="✕"></span>
                            </label>
                            <span class="align-top {{ $accion == 'eliminar' ? 'text-danger' : '' }}">{{ Str::title($accion) }}</span>
                        </div>
                    </div>
                    @php
                        $moduloAnterior = $moduloActual;
                    @endphp
                @endforeach
            </div>
        </div>
        <div class="card-footer">
            @if(isset($role) && $role->name !== 'Administrador')
                @can('editar_roles')
                    <div class="w-100 text-center">
                        {!! Form::submit('Guardar permisos de ' . $role->name, ['class' => 'btn btn-primary']) !!}
                    </div>
                @endcan
            @endif
        </div>
    </div>
</div>
