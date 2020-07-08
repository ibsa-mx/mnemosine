<?php

use Mnemosine\Module;
use Illuminate\Database\Seeder;

class ModulesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $moduleInventario = [
            'name' => 'inventario',
            'label' => 'Inventario',
            'active' => 1,
            'order' => 1
        ];

        $moduleInvestigacion = [
            'name' => 'investigacion',
            'label' => 'Investigación',
            'active' => 1,
            'order' => 2
        ];

        $moduleRestauracion = [
            'name' => 'restauracion',
            'label' => 'Restauración',
            'active' => 1,
            'order' => 3
        ];

        if ($moduleInventarioRes = Module::create($moduleInventario)) {
            $this->command->info('Se agrego el modulo inventario.');
        } else {
            $this->command->warn('No se pudo agregar el modulo inventario.');
        }

        if ($moduleInvestigacionRes = Module::create($moduleInvestigacion)) {
            $this->command->info('Se agrego el modulo investigación.');
        } else {
            $this->command->warn('No se pudo agregar el modulo investigación.');
        }

        if ($moduleRestauracionRes = Module::create($moduleRestauracion)) {
            $this->command->info('Se agrego el modulo restauración.');
        } else {
            $this->command->warn('No se pudo agregar el modulo restauración.');
        }
    }
}
