<?php

use Mnemosine\User;
use Mnemosine\Role;
use Mnemosine\Permission;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(PiecesTableSeeder::class);
        $this->call(ResearchsTableSeeder::class);
        $this->call(RestorationsTableSeeder::class);
        // Ask for db migration refresh, default is no
        if ($this->command->confirm('¿Deseas reconstruir la base de datos antes de alimentarla?, esto limpiará todos los datos antoguos.')) {
            // Call the php artisan migrate:refresh
            $this->command->call('migrate:refresh');
            $this->command->warn("Se han eliminado los registros, iniciando con una base de datos en blanco.");
        }

        // Seed the default permissions
        $permissions = Permission::defaultPermissions();

        foreach ($permissions as $perms) {
            Permission::firstOrCreate(['name' => $perms]);
        }

        $this->command->info('Se han agregado los permisos por defecto.');

        // Confirm roles needed
        if ($this->command->confirm('¿Crear roles para usuarios?, por defecto son Administrador y Investigador [y|N]', true)) {

            // Ask for roles from input
            $input_roles = $this->command->ask('Escribe los roles separados por comas.', 'Administrador,Investigador');

            // Explode roles
            $roles_array = explode(',', $input_roles);

            // add roles
            foreach($roles_array as $role) {
                $role = Role::firstOrCreate(['name' => trim($role)]);

                if( $role->name == 'Administrador' ) {
                    // assign all permissions
                    $role->syncPermissions(Permission::all());
                    $this->command->info('Se han actualizado los permisos del administrador');
                } else {
                    // for others by default only read access
                    $role->syncPermissions(Permission::where('name', 'LIKE', 'ver_%')->get());
                }

                // create one user for each role
                $this->createUser($role);
            }

            $this->command->info('Roles ' . $input_roles . ' se han agregado correctamente');

        } else {
            Role::firstOrCreate(['name' => 'User']);
            $this->command->info('Se agrego solamente el role por defecto.');
        }

    }

    /**
     * Create a user with given role
     *
     * @param $role
     */
    private function createUser($role)
    {
        $user = factory(User::class)->create();
        $user->assignRole($role->name);

        if( $role->name == 'Administrador' ) {
            $this->command->info('Aqui está tu información de identificación para tu usuario Administrador:');
            $this->command->warn($user->email);
            $this->command->warn('La contraseña es "secret"');
        }
    }
}
