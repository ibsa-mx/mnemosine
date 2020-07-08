@php
    $checkCustom = true;
    $checkAll = false;
    $checkAllExcept = false;
    if(isset($reporte)){
        if($reporte->select_type == 'all'){
            $checkCustom = false;
            $checkAll = true;
        } else if($reporte->select_type == 'all_except'){
            $checkCustom = false;
            $checkAllExcept = true;
        }
    }
@endphp

<div id="tab-3" role="tabpanel" class="bs-stepper-pane" aria-labelledby="stepper1trigger3">
    <div class="input-group mb-3 justify-content-center alert alert-warning">
        <div class="custom-control custom-radio custom-control-inline mr-5">
            <input type="radio" id="radio_select_custom" name="select_type" value="custom" class="custom-control-input"{{ $checkCustom ? " checked" : "" }}>
            <label class="custom-control-label" for="radio_select_custom">Selección personalizada</label>
        </div>
        <div class="custom-control custom-radio custom-control-inline">
            <input type="radio" id="radio_select_all" name="select_type" value="all" class="custom-control-input"{{ $checkAll ? " checked" : "" }}>
            <label class="custom-control-label" for="radio_select_all">Seleccionar todas las piezas</label>
        </div>
        <div class="custom-control custom-radio custom-control-inline">
            <input type="radio" id="radio_select_all_except" name="select_type" value="all_except" class="custom-control-input"{{ $checkAllExcept ? " checked" : "" }}>
            <label class="custom-control-label" for="radio_select_all_except">Seleccionar todas las piezas, excepto las siguientes</label>
        </div>
    </div>
    {{-- ACCIONES --}}
    <div class="text-center mt-3">
        <a class="btn btn-danger" href="{{route('reportes.index')}}"><i class="fas fa-ban"></i> Cancelar</a>
        <button class="btn btn-warning" type="button" onclick="stepper1.previous()"><i class="fas fa-step-backward"></i> Anterior</button>
        <button type="submit" class="btn btn-success" id="btn-generar-reporte-alt"><i class="far fa-save"></i>{{(isset($reporte) ? ' Editar': ' Guardar')}} y generar reporte</button>
    </div>
    <div id="content_select_custom"@if ($checkAll) style="display: none;" @endif>
        <div class="mb-3">
            <span class="badge badge-info" id="piezasCargadas">
                @if(isset($ids) && ($ids!=null))
                    {!! count($ids) !!}
                @else
                    0
                @endif
            </span>
            <input type="hidden" name="pieces_ids" id="hidden_pieces_ids" value="" />
            <strong>Piezas cargadas</strong>
            <select class="form-control @error('pieces_ids') is-invalid @enderror" id="piezas_id" multiple="multiple" style="resize: vertical;">
                @if(isset($ids) && ($ids!=null))
                    @if(is_array($ids) || is_object($ids))
                        @foreach($piezas as $pieza)
                            @foreach($ids as $item)
                                @if($pieza->id == $item)
                                    <option value="{{$pieza->id}}" selected="selected">
                                        {{$pieza->inventory_number}} /
                                        {{$pieza->catalog_number}}
                                    </option>
                                @endif
                            @endforeach
                        @endforeach
                    @endif
                @endif
            </select>
            @error('pieces_ids')
                <div class="invalid-feedback">
                    {{ $errors->first('pieces_ids') }}
                </div>
            @enderror
        </div>

        <div class="mb-3 row justify-content-center">
            <div class="col-md-4">
                <button type="button" id="btn-cargar-piezas" class="btn btn-primary btn-block"><i class="fas fa-chevron-up"></i> Agregar piezas</button>
            </div>
        </div>

        {!! $dataTable->table([], true) !!}
        {{-- ACCIONES --}}
        <div class="text-center mt-3">
            <a class="btn btn-danger" href="{{route('reportes.index')}}"><i class="fas fa-ban"></i> Cancelar</a>
            <button class="btn btn-warning" type="button" onclick="stepper1.previous()"><i class="fas fa-step-backward"></i> Anterior</button>
            <button type="submit" class="btn btn-success" id="btn-generar-reporte"><i class="far fa-save"></i>{{(isset($reporte) ? ' Editar': ' Guardar')}} y generar reporte</button>
        </div>
    </div>
</div>
