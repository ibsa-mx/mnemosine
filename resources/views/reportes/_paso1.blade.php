<div id="tab-1" role="tabpanel" class="bs-stepper-pane" aria-labelledby="stepper1trigger1">
    <div class="text-center mb-3">
        <a class="btn btn-danger" href="{{route('reportes.index')}}"><i class="fas fa-ban"></i> Cancelar</a>
        <button class="btn btn-warning" type="button" name="btn-sig1" id="btn-sig1-alt">Siguiente <i class="fas fa-step-forward"></i></button>
    </div>
    <div class="form-group">
        <div class="input-group mb-3 @error('name') has-error @enderror">
            <div class="input-group-prepend">
                <span class="input-group-text">Nombre del reporte</span>
            </div>
            {!! Form::text('name', null, ['id' => 'reportName', 'class' => $errors->has('name') ? 'form-control is-invalid' : 'form-control', 'placeholder' => '', 'required' => 'required']) !!}
            @error('name')
                <div class="invalid-feedback">
                    {{ $errors->first('name') }}
                </div>
            @enderror
        </div>

        <div class="input-group mb-3 @error('description') has-error @enderror">
            <div class="input-group-prepend">
                <span class="input-group-text">Descripción</span>
            </div>
            {!! Form::textarea('description', null, ['class' => $errors->has('description') ? 'form-control is-invalid' : 'form-control', 'placeholder' => '', 'rows' => '3']) !!}
            @error('description')
                <div class="invalid-feedback">
                    {{ $errors->first('description') }}
                </div>
            @enderror
        </div>
        <div class="mb-3 border rounded-top @error('columnas') has-error @enderror">
            <select size="20" name="columnas[]" id="columnas" multiple="multiple" required="required">
                @foreach ($columnas as $idx => $campo)
                    <option value="{{$idx}}" {!! isset($columnasSeleccionadas) && in_array($idx, $columnasSeleccionadas) ? " selected" : "" !!}>
                        {{$campo}}
                    </option>
                @endforeach
            </select>
            @error('columnas')
                <div class="invalid-feedback">
                    {{ $errors->first('columnas') }}
                </div>
            @enderror
        </div>
        <div class="input-group mb-3 justify-content-center alert alert-warning pb-2 pt-3">
            <label class="switch switch-label switch-pill switch-primary align-bottom">
                {!! Form::checkbox('custom_order', '1', isset($reporte->custom_order) ? $reporte->custom_order : false, ['class' => 'switch-input', 'id' => 'chk_custom_order']) !!}
                <span class="switch-slider" data-checked="Sí" data-unchecked="No"></span>
            </label>
            <label for="chk_custom_order" class="align-top ml-3">
                ¿Personalizar orden de las columnas?
            </label>
        </div>
        <input type="hidden" name="columnas_ordenadas" id="columnasOrdenadas" value="@if (isset($reporte) && isset($reporte->custom_order) && $reporte->custom_order) {!! implode(",", $columnasSeleccionadas) !!} @endif"/>
        <div class="w-50 m-auto">
            <ul id="ordenColumnas" class="list-group" @if (!isset($reporte->custom_order) || !$reporte->custom_order) style="display: none;" @endif>
                @isset($reporte)
                    @foreach ($columnasSeleccionadas as $idx => $campo)
                        <li data-id="{{$campo}}" class="list-group-item list-group-item-secondary">
                            <i class="fas fa-arrows-alt"></i> {{$columnas[$campo]}}
                        </li>
                    @endforeach
                @endisset
            </ul>
        </div>
    </div>
    <div class="text-center">
        <a class="btn btn-danger" href="{{route('reportes.index')}}"><i class="fas fa-ban"></i> Cancelar</a>
        <button class="btn btn-warning" type="button" name="btn-sig1" id="btn-sig1">Siguiente <i class="fas fa-step-forward"></i></button>
    </div>
</div>
