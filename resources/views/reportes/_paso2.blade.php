<div id="tab-2" role="tabpanel" class="bs-stepper-pane" aria-labelledby="stepper1trigger2">
    <div class="input-group mb-3 justify-content-center alert alert-warning pb-2 pt-3">
        <label class="switch switch-label switch-pill switch-primary align-bottom">
            {!! Form::checkbox('lending_list', '1', isset($reporte->lending_list) ? $reporte->lending_list : false, ['class' => 'switch-input', 'id' => 'chk_lending_list']) !!}
            <span class="switch-slider" data-checked="Sí" data-unchecked="No"></span>
        </label>
        <label for="chk_lending_list" class="align-top ml-3">
            ¿Es un listado para préstamo de obra?
        </label>
    </div>

    <div class="form-group" id="div_lending_list"@if (!isset($reporte->lending_list) || !$reporte->lending_list) style="display: none;" @endif>
        <div class="input-group mb-3 @error('institution') has-error @enderror">
            <div class="input-group-prepend">
                <span class="input-group-text">Institución solicitante</span>
            </div>
            {!! Form::text('institution', null, ['class' => $errors->has('institution') ? 'form-control is-invalid' : 'form-control']) !!}
            @error('institution')
                <div class="invalid-feedback">
                    {{ $errors->first('institution') }}
                </div>
            @enderror
        </div>
        <div class="input-group mb-3 @error('exhibition') has-error @enderror">
            <div class="input-group-prepend">
                <span class="input-group-text">Exposición</span>
            </div>
            {!! Form::text('exhibition', null, ['class' => $errors->has('exhibition') ? 'form-control is-invalid' : 'form-control']) !!}
            @error('exhibition')
                <div class="invalid-feedback">
                    {{ $errors->first('exhibition') }}
                </div>
            @enderror
        </div>
        <div class="input-group mb-3 @error('exhibition_date_ini') has-error @enderror">
            <div class="input-group-prepend">
                <span class="input-group-text">Fecha de exhibición</span>
            </div>
            <input type="text" name="exhibition_date_ini" class="form-control{!! $errors->has('exhibition_date_ini') ? ' is-invalid' : '' !!}" />
            @error('exhibition_date_ini')
                <div class="invalid-feedback">
                    {{ $errors->first('exhibition_date_ini') }}
                </div>
            @enderror
        </div>
        <div class="input-group mb-3 @error('exhibition_date_fin') has-error @enderror">
            <div class="input-group-prepend">
                <span class="input-group-text">Fecha final de exhibición</span>
            </div>
            <input type="text" name="exhibition_date_fin" class="form-control{!! $errors->has('exhibition_date_fin') ? ' is-invalid' : '' !!}" />
            @error('exhibition_date_fin')
                <div class="invalid-feedback">
                    {{ $errors->first('exhibition_date_fin') }}
                </div>
            @enderror
        </div>
        {{-- <div class="input-group mb-3">
            <div class="input-group-prepend">
                <span class="input-group-text">
                    <label for="chk_consecutive_number" class="align-middle">Incluir número consecutivo</label>
                </span>
            </div>
            <div class="border border-secondary rounded-right flex w-auto">
                <label class="switch switch-label switch-pill switch-primary align-bottom mt-2 mx-3">
                    {!! Form::checkbox('consecutive_number', '1', isset($reporte->consecutive_number) ? $reporte->consecutive_number : false, ['class' => 'switch-input', 'id' => 'chk_consecutive_number']) !!}
                    <span class="switch-slider" data-checked="Sí" data-unchecked="No"></span>
                </label>
            </div>
        </div> --}}
    </div>

    {{-- ACCIONES --}}
    <div class="text-center">
        <a class="btn btn-danger" href="{{route('reportes.index')}}"><i class="fas fa-ban"></i> Cancelar</a>
        <button class="btn btn-warning" type="button" onclick="stepper1.previous()"><i class="fas fa-step-backward"></i> Anterior</button>
        <button class="btn btn-warning" type="button" name="btn-sig2" id="btn-sig2">Siguiente <i class="fas fa-step-forward"></i></button>
    </div>
</div>
