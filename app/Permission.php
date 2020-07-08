<?php

namespace Mnemosine;

use Illuminate\Database\Eloquent\Model;

class Permission extends \Spatie\Permission\Models\Permission
{
    public static function defaultPermissions(){
        return [
            'ver_usuarios',
            'agregar_usuarios',
            'editar_usuarios',
            'eliminar_usuarios',

            'ver_roles',
            'agregar_roles',
            'editar_roles',
            'eliminar_roles',

            'ver_inventario',
            'agregar_inventario',
            'editar_inventario',
            'eliminar_inventario',

            'ver_investigacion',
            'agregar_investigacion',
            'editar_investigacion',
            'eliminar_investigacion',

            'ver_restauracion',
            'agregar_restauracion',
            'editar_restauracion',
            'eliminar_restauracion',

            'ver_movimientos',
            'agregar_movimientos',
            'editar_movimientos',
            'eliminar_movimientos',

            'ver_reportes',
            'agregar_reportes',
            'editar_reportes',
            'eliminar_reportes',

            'ver_configuraciones',
            'agregar_configuraciones',
            'editar_configuraciones',
            'eliminar_configuraciones',
        ];
    }
}
