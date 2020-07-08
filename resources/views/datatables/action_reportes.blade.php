<div class="custom-control custom-checkbox">
    {{ Form::checkbox('check_p[]', $piece->id, false, [
            'class' => 'custom-control-input p_id',
            'id' => 'id_'.$piece->id,
            'data-inventory-number' => $piece->inventory_number,
            'data-catalog-number' => $piece->catalog_number,
        ]) }}
    <label class="custom-control-label" for="id_{{$piece->id}}"></label>
</div>
