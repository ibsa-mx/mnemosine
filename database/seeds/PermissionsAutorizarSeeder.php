<?php

use Mnemosine\Role;
use Mnemosine\Permission;
use Illuminate\Database\Seeder;

class PermissionsAutorizarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = $this->getPermissions();
        // create permissions
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission ]);
        }
        $this->command->info('Se han creado los permisos ' . implode(', ', $permissions) . '.');

        // sync role for admin
        if( $role = Role::where('name', 'Administrador')->first() ) {
            $role->syncPermissions(Permission::all());
            $this->command->info('Se han actualizado los permisos del administrador');
        }
    }

    public static function getPermissions(){
        return [
            'autorizar_inventario',
            'autorizar_investigacion',
            'autorizar_restauracion',
            'autorizar_movimientos',
        ];
    }
}
