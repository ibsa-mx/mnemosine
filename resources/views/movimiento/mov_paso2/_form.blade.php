@if (count($errors) > 0)
    <div class="alert alert-danger">
        Hubo problemas con los datos ingresados.<br/><br/>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
{!! $dataTable->table([], true) !!}
{!! $dataTable->scripts() !!}


<div class="row justify-content-center">
    <div class="col-md-4">
        <button type="button" id="btn-cargar-piezas" class="btn btn-primary btn-block">
            Cargar piezas <i class="fas fa-chevron-down"></i>
        </button>
    </div>
</div>

    <div class="pt-3">
        <span class="badge badge-info" id="piezasCargadas">
            @if(isset($ids) && ($ids!=null))
                {!! count($ids) !!}
            @else
                0
            @endif
        </span>
        <label>Piezas cargadas</label>
        <input type="hidden" name="pieces_ids" id="hidden_pieces_ids" value="" />
        <select class="form-control @error('pieces_ids') is-invalid @enderror" style="resize: vertical;" id="piezas_id" multiple="multiple">
            @if(isset($ids) && ($ids!=null))
                @if(is_array($ids) || is_object($ids))
                    @foreach($piezas as $p)
                        @foreach($ids as $item)
                            @if($p->id == $item)
                                <option value="{{$p->id}}" selected="selected">{{$p->inventory_number}} / {{$p->catalog_number}}</option>
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
    <div class="pt-3 text-center">
        <div class="custom-control custom-checkbox mb-2">
            {!! Form::checkbox('paso3', '1', true, ['class' => 'custom-control-input', 'id' => 'paso3']) !!}
            <label class="custom-control-label text-danger" for="paso3">Ir al paso 3</label>
        </div>
        <input type="hidden" name="p2" value="2">
        <button type="submit" class="btn btn-primary" id="guardarMovimiento">Guardar</button>
    </div>
