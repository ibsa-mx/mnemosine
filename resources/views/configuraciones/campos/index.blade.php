@extends('admin.layout')

@section('title', '- Modificar campos')

@section('breadcrumb')
    <li class="breadcrumb-item">
        Modificar campos
    </li>
@endsection

@section('assets')
    <script src="{{ asset('admin/js/campos.js') }}"></script>
    <style>
    .sortable {
        list-style-type: none;
        margin: 0;
        padding: 0;
    }
    .sortable li {
        margin: 0;
        cursor: default;
        border-left: 0.25em solid #20a8d8 !important;
        border-radius: 0 !important;
    }
    .sortable .grip { position: absolute; margin-left: -1.2em; margin-top: 0.25em; cursor: move; }
    .ui-state-highlight { height: auto; width: 50%; line-height: 1.2em; background: lightgray; border: 1px solid gray !important; }
    .card-title{
        font-size: 1.5em;
    }
    </style>
    <script type="text/javascript">
        var modulos = {
        @foreach ($modules as $module)
            '{{$module->name}}': '{{$module->id}}',
        @endforeach
        };
    </script>
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-6">
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text">Seleccione un módulo</span>
                    </div>
                    <select class="form-control select2" id="modulo" aria-label="Módulo" aria-describedby="modulo" required="required">
                        @foreach ($modules as $module)
                            <option value="{{$module->name}}">{{$module->label}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-6 text-right">
                <button type="button" class="btn btn-outline-primary btn-sm mb-3" data-toggle="modal" data-target="#modalGrupoCampos"><i class="fas fa-plus"></i> Agregar grupo de campos</button>
            </div>
        </div>
        <!-- Modal para grupo de campos -->
        <div class="modal fade" id="modalGrupoCampos" tabindex="-1" role="dialog" aria-labelledby="agregarGrupoCampos" aria-hidden="true">
          <div class="modal-dialog" role="document">
            {!! Form::open(['route' => ['campos.store'] ]) !!}
            {!! Form::hidden('module_id', '0') !!}
            <div class="modal-content">
              <div class="modal-header alert-dark">
                <h5 class="modal-title" id="agregarGrupoCampos">Agregar grupo de campos</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                  <div class="input-group mb-3">
                      <div class="input-group-prepend">
                          <span class="input-group-text">Nombre</span>
                      </div>
                      {!! Form::text('label', null, ['class' =>  'form-control', 'required']) !!}
                  </div>
                  <div class="input-group mb-3">
                      <div class="input-group-prepend">
                          <span class="input-group-text">Mostrar después de</span>
                      </div>
                      <select name="order" class="form-control select2">
                      @foreach ($fieldGroups as $fieldGroup)
                          <option value="{{$fieldGroup->order}}" data-module-id="{{$fieldGroup->module_id}}">{{$fieldGroup->label}}</option>
                      @endforeach
                      </select>
                  </div>
                  <div class="text-center">
                      <div class="checkbox">
                          <label class="switch switch-label switch-pill switch-outline-info-alt">
                              {!! Form::checkbox("active", 'active', true, ['class' => 'switch-input', 'id' => 'mostrarGrupo']) !!}
                              <span class="switch-slider" data-checked="✓" data-unchecked="✕" data-toggle="tooltip" data-original-title="Permite mostrar u ocultar el grupo de campos"></span>
                          </label>
                          <label class="align-top" for="mostrarGrupo">Mostrar</label>
                      </div>
                  </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                {!! Form::submit('Crear grupo', ['class' => 'btn btn-primary']) !!}
              </div>
            </div>
            {!! Form::close() !!}
          </div>
        </div>
        <!-- Modal para campo nuevo -->
        <div class="modal fade" id="modalCampo" tabindex="-1" role="dialog" aria-labelledby="agregarCampo" aria-hidden="true">
          <div class="modal-dialog modal-lg" role="document">
            {!! Form::open(['route' => ['campos.store'] ]) !!}
            {!! Form::hidden('field_group_id', '0') !!}
            <div class="modal-content">
              <div class="modal-header alert-dark">
                <h5 class="modal-title" id="agregarCampo">Agregar nuevo campo</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                  <div class="row">
                      <div class="col">
                          <div class="input-group mb-3">
                              <div class="input-group-prepend">
                                  <span class="input-group-text">Tipo de campo</span>
                              </div>
                              {!! Form::select('type', $tiposCampos, '', ['class' => 'form-control select2', 'required']) !!}
                          </div>
                      </div>
                      <div class="col">
                          <div class="input-group mb-3">
                              <div class="input-group-prepend">
                                  <span class="input-group-text">Nombre</span>
                              </div>
                              {!! Form::text('name', null, ['class' =>  'form-control']) !!}
                          </div>
                      </div>
                  </div>
                  <div class="row">
                      <div class="col">
                          <div class="input-group mb-3" id="campoEtiqueta">
                              <div class="input-group-prepend">
                                  <span class="input-group-text">Etiqueta</span>
                              </div>
                              {!! Form::text('label', null, ['class' =>  'form-control']) !!}
                          </div>
                          <div class="input-group mb-3 d-none" id="campoCatalogo">
                              <div class="input-group-prepend">
                                  <span class="input-group-text">Catálogo</span>
                              </div>
                              <select name="catalog_id" class="form-control select2">
                                  @foreach ($catalogs as $catalog)
                                      <option value="{{$catalog->id}}" title="{{$catalog->description}}">{{$catalog->title}}</option>
                                  @endforeach
                              </select>
                          </div>
                      </div>
                      <div class="col">
                          <div class="input-group mb-3">
                              <div class="input-group-prepend">
                                  <span class="input-group-text">Descripción</span>
                              </div>
                              {!! Form::text('placeholder', null, ['class' =>  'form-control']) !!}
                          </div>
                      </div>
                  </div>
                  <div class="row">
                      <div class="col-8">
                          <div class="input-group mb-3">
                              <div class="input-group-prepend">
                                  <span class="input-group-text">Editable en módulos</span>
                              </div>
                              <select name="editable_in_modules[]" class="form-control select2" multiple="multiple">
                                  @foreach ($modules as $module)
                                      <option value="{{$module->name}}">{{$module->label}}</option>
                                  @endforeach
                              </select>
                          </div>
                      </div>
                      <div class="col-4" id="campoTamanio">
                          <div class="input-group mb-3">
                              <div class="input-group-prepend">
                                  <span class="input-group-text">Tamaño</span>
                              </div>
                              {!! Form::text('length', null, ['class' =>  'form-control', 'placeholder' => 'Limitar caracteres']) !!}
                          </div>
                      </div>
                  </div>
                  <div class="row">
                      <div class="col">
                          <div class="checkbox">
                              <label class="switch switch-label switch-pill switch-outline-info-alt">
                                  {!! Form::checkbox("options[]", 'active', true, ['class' => 'switch-input', 'id' => 'mostrarCampo']) !!}
                                  <span class="switch-slider" data-checked="✓" data-unchecked="✕" data-toggle="tooltip" data-original-title="Permite mostrar u ocultar el campo"></span>
                              </label>
                              <label class="align-top" for="mostrarCampo">Mostrar</label>
                          </div>
                      </div>
                      <div class="col">
                          <div class="checkbox">
                              <label class="switch switch-label switch-pill switch-outline-info-alt">
                                  {!! Form::checkbox("options[]", 'list-view', false, ['class' => 'switch-input', 'id' => 'verListado']) !!}
                                  <span class="switch-slider" data-checked="✓" data-unchecked="✕" data-toggle="tooltip" data-original-title="Permite que el campo se muestre u oculte en el listado del módulo principal"></span>
                              </label>
                              <label class="align-top text-nowrap" for="verListado">Ver en listado</label>
                          </div>
                      </div>
                      <div class="col">
                          <div class="checkbox">
                              <label class="switch switch-label switch-pill switch-outline-danger-alt">
                                  {!! Form::checkbox("options[]", 'required', false, ['class' => 'switch-input', 'id' => 'campoObligatorio']) !!}
                                  <span class="switch-slider" data-checked="✓" data-unchecked="✕" data-toggle="tooltip" data-original-title="Si se activa, el campo debe ser rellenado para que puedan guardarse todos los datos"></span>
                              </label>
                              <label class="align-top text-danger" for="campoObligatorio" id="campoObligatorioLabel">Obligatorio</label>
                          </div>
                      </div>
                      <div class="col">
                          <div class="checkbox">
                              <label class="switch switch-label switch-pill switch-outline-info-alt">
                                  {!! Form::checkbox("options[]", 'summary_view', false, ['class' => 'switch-input', 'id' => 'verResumen']) !!}
                                  <span class="switch-slider" data-checked="✓" data-unchecked="✕" data-toggle="tooltip" data-original-title="Permite que el campo se muestre u oculte en el resumen de otros módulos"></span>
                              </label>
                              <label class="align-top text-nowrap" for="verResumen">Ver en resumen</label>
                          </div>
                      </div>
                  </div>
                  <hr/>
                  <div class="text-center">
                      <div class="checkbox">
                          <label class="switch switch-label switch-pill switch-outline-info-alt">
                              {!! Form::checkbox("options[]", 'role_permission', false, ['class' => 'switch-input', 'id' => 'asignarPermisos']) !!}
                              <span class="switch-slider" data-checked="✓" data-unchecked="✕" data-toggle="tooltip" data-original-title="Si se activa, permite que el campo sea visible solamente para ciertos roles de usuarios, con esta opción el campo no puede ser obligatorio"></span>
                          </label>
                          <label class="align-top text-nowrap" for="asignarPermisos">Asignar permisos por rol</label>
                      </div>
                      <div class="form-group text-left d-none" id="verPermisos">
                          <label for="roles" class="col-form-label">Seleccione los roles que pueden ver el campo</label>
                          <br />
                          <select name="roles_ids[]" class="form-control select2" id="roles" multiple="multiple">
                              @foreach ($roles as $role)
                                  <option value="{{$role->id}}">{{$role->name}}</option>
                              @endforeach
                          </select>
                      </div>
                  </div>
                  <div class="text-center">
                      <div class="checkbox">
                          <label class="switch switch-label switch-pill switch-outline-info-alt">
                              {!! Form::checkbox("options[]", 'gender_view', false, ['class' => 'switch-input', 'id' => 'asignarGeneros']) !!}
                              <span class="switch-slider" data-checked="✓" data-unchecked="✕" data-toggle="tooltip" data-original-title="Si se activa, permite vincular el campo con una o más generos para que solo aparezca en ellas, con esta opción el campo no puede ser obligatorio"></span>
                          </label>
                          <label class="align-top text-nowrap" for="asignarGeneros">Vincular con generos</label>
                      </div>
                      <div class="form-group text-left d-none" id="verGeneros">
                          <label for="roles" class="col-form-label">Seleccione a que generos se debe vincular el campo</label>
                          <br />
                          <select name="roles_ids[]" class="form-control select2" id="genders" multiple="multiple">
                              @foreach ($genders as $gender)
                                  <option value="{{$gender->id}}" title="{{$gender->description}}">{{$gender->title}}</option>
                              @endforeach
                          </select>
                      </div>
                  </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                {!! Form::submit('Crear campo', ['class' => 'btn btn-primary']) !!}
              </div>
            </div>
            {!! Form::close() !!}
          </div>
        </div>
    @foreach ($modules as $module)
        <div id="modulo_{{$module->name}}" class="modulo {{ $module->active == 1 ? 'active' : 'inactive' }}{{ $module->id != 1 ? ' d-none' : '' }}">
        @foreach ($fieldGroups as $idxGroup => $fieldGroup)
            @if ($module->id != $fieldGroup->module_id)
                @continue
            @endif
            <div class="card card-accent-info">
                <div class="card-header">
                    <span class="card-title">{{$fieldGroup->label}}</span>
                    <div class="card-header-actions">
                        @can('agregar_configuraciones')
                            <button type="button" class="btn btn-outline-primary btn-sm card-header-action modal-campo" data-toggle="modal" data-target="#modalCampo" data-id="campo{{$fieldGroup->id}}" data-toggle="tooltip" data-original-title="Agrega un campo a este grupo"><i class="fas fa-plus"></i> Agregar campo</button>
                        @endcan
                        @can('editar_configuraciones')
                            <button type="button" class="btn btn-outline-primary btn-sm card-header-action" data-id="guardarOrden{{$fieldGroup->id}}" data-toggle="tooltip" data-original-title="Guardar el nuevo orden de los campos"><i class="far fa-save"></i> Guardar orden</button>
                        @endcan
                        @if ($fieldGroup->order > 1)
                            @can('editar_configuraciones')
                                <a class="card-header-action" href="#" data-toggle="tooltip" data-original-title="Configuración del grupo de campos">
                                    <i class="nav-icon icon-settings"></i>
                                </a>
                            @endcan
                            <a class="card-header-action{{ $fieldGroup->order > 2 ? "" : " d-none" }}" href="#" data-toggle="tooltip" data-original-title="Subir módulo">
                                <i class="icon-arrow-up"></i>
                            </a>
                            <a class="card-header-action" href="#" title="" data-original-title="Bajar modulo">
                                <i class="icon-arrow-down"></i>
                            </a>
                        @endif
                    </div>
                </div>
                <div class="card-body p-0">
                    @if ($fields->count()>0)
                    <ul class="sortable list-group d-flex flex-row flex-wrap">
                        @foreach ($fields as $idxField => $field)
                            @if ($field->field_group_id != $fieldGroup->id)
                                @continue
                            @endif
                        <li class="list-group-item list-group-item-action w-50 m-0 mb-1 py-1">
                            <div class="row">
                                <div class="col-4 border-right">
                                    <i class="fas fa-grip-vertical grip"></i> <strong>{{$field->label}}</strong>
                                    <p class="text-muted m-0">{{$tiposCampos[$field->type]}}</p>
                                </div>
                                <div class="col-7 pl-4">
                                    <span class="mr-3 text-nowrap{{ $field->required == 1 ? '' : ' text-secondary' }}"><i class="fas fa-exclamation"></i> Requerido</span>
                                    <span class="mr-3 text-nowrap{{ $field->active == 1 ? '' : ' text-secondary' }}"><i class="fas fa-{{ $field->active == 1 ? 'check' : 'times' }}"></i> {{ $field->active == 1 ? 'Mostrar' : 'Ocultar' }}</span>
                                    <span class="mr-3 text-nowrap{{ $field->summary_view == 1 ? '' : ' text-secondary' }}"><i class="far fa-sticky-note"></i> Ver en resumen</span>
                                </div>
                                <div class="col-1 text-right">
                                @can('editar_configuraciones')
                                    <button class="btn btn-outline-primary btn-sm m-0 mx-n2"><i class="far fa-edit m-0 position-static"></i></button>
                                @endcan
                                @can('eliminar_configuraciones')
                                    <button class="btn btn-outline-danger btn-sm m-0 mt-1 ml-n2"><i class="far fa-trash-alt m-0 position-static"></i></button>
                                @endcan
                                </div>
                            </div>
                        </li>
                        @endforeach
                    </ul>
                    @else
                        <div class="alert alert-warning">
                            Aun no se han asignado campos a este grupo
                        </div>
                    @endif
                </div>
            </div>
        @endforeach
        </div>
    @endforeach

    </div>
    @include('flash-toastr::message')
    @include('shared.jquery-ui')
@endsection
