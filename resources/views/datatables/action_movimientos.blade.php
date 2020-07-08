<div class="custom-control custom-checkbox">
    {{ Form::checkbox('pieces_id[]', $piece->id, in_array($piece->id, $pieceIds), [
            'class' => 'custom-control-input p_id',
            'id' => 'id_'.$piece->id,
            // 'data-indeterminate' => in_array($piece->id, $pieceIds) ? '0' : (integer)$piece->in_exhibition,
            'data-inventory-number' => $piece->inventory_number,
            'data-catalog-number' => $piece->catalog_number,
            'data-loaded' => in_array($piece->id, $pieceIds) ? '1' : '0'
        ]) }}
    <label class="custom-control-label" for="id_{{$piece->id}}"></label>
</div>
