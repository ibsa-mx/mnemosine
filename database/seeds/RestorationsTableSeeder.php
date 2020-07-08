<?php

use Illuminate\Database\Seeder;
use Mnemosine\Restoration;

class RestorationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    
       if ($restauracionesRes = factory(Restoration::class, 2000)->create()) {
    		$this->command->info('Se agregaron las restauraciones.');
		} else {
    		$this->command->warn('No se pudo agregar.');
		}
    }
}
