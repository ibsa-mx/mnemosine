@php
	$module = isset($parentModule) ? $parentModule : $entity;
@endphp
@if(isset($search) && $search != 0)
	@can('ver_'.$module)
	    <a href="{{ route($entity.'.show', [str_singular($entity) => $id]) }}" class="btn btn-sm btn-outline-primary" rel="tooltip" title="Ver {{ $singular }}"><i class="far fa-eye"></i></a>
	@endcan
@endif

@can('editar_'.$module)
    <a href="{{ route($entity.'.edit', [str_singular($entity) => $id]) }}" class="btn btn-sm btn-outline-primary" rel="tooltip" title="Editar {{ $singular }}"><i class="far fa-edit"></i></a>
@endcan

@can('eliminar_'.$module)
    {!! Form::open( ['method' => 'delete', 'url' => route($entity.'.destroy', [str_singular($entity) => $id]), 'style' => 'display: inline', 'onSubmit' => 'return confirm("¿Realmente desea eliminar el elemento?")']) !!}
        <button type="submit" class="btn-delete btn btn-sm btn-outline-danger" rel="tooltip" title="Eliminar {{ $singular }}">
            <i class="far fa-trash-alt"></i>
        </button>
    {!! Form::close() !!}
@endcan
