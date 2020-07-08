<?php

namespace Mnemosine\Console\Commands;

use Illuminate\Console\Command;
use Mnemosine\Permission;
use Mnemosine\Role;

class AuthPermissionCommand extends Command
{
    /**
    * The name and signature of the console command.
    *
    * @var string
    */
    protected $signature = 'auth:permission {name} {--R|remove}';

    /**
    * The console command description.
    *
    * @var string
    */
    protected $description = 'Command description';

    /**
    * Create a new command instance.
    *
    * @return void
    */
    public function __construct()
    {
        parent::__construct();
    }

    /**
    * Execute the console command.
    *
    * @return mixed
    */
    public function handle()
    {
        $permissions = $this->generatePermissions();

        // check if its remove
        if( $is_remove = $this->option('remove') ) {
            // remove permission
            if( Permission::where('name', 'LIKE', '%'. $this->getNameArgument())->delete() ) {
                $this->warn('Se han eliminado los permisos ' . implode(', ', $permissions) . '.');
            }  else {
                $this->warn('No se han encontrado permisos para ' . $this->getNameArgument());
            }
        } else {
            // create permissions
            foreach ($permissions as $permission) {
                Permission::firstOrCreate(['name' => $permission ]);
            }
            $this->info('Se han creado los permisos ' . implode(', ', $permissions) . '.');
        }

        // sync role for admin
        if( $role = Role::where('name', 'Administrador')->first() ) {
            $role->syncPermissions(Permission::all());
            $this->info('Se han actualizado los permisos del administrador');
        }
    }

    private function generatePermissions()
    {
        $abilities = ['ver', 'agregar', 'editar', 'eliminar'];
        $name = $this->getNameArgument();

        return array_map(function($val) use ($name) {
            return $val . '_'. $name;
        }, $abilities);
    }

    private function getNameArgument()
    {
        return strtolower(str_plural($this->argument('name')));
    }
}
