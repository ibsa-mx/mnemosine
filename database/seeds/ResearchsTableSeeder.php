<?php

use Illuminate\Database\Seeder;
use Mnemosine\Research;

class ResearchsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

      if ($investigacionesRes = factory(Research::class, 2000)->create()) {
    		$this->command->info('Se agregaron las investigaciones.');
		} else {
    		$this->command->warn('No se pudo agregar.');
		}
    }
}
