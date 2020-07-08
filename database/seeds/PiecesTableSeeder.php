<?php

use Illuminate\Database\Seeder;
use Mnemosine\Piece;

class PiecesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

    		if ($piezasRes = factory(Piece::class, 12000)->create()) {
	    		$this->command->info('Se agregaron las piezas.');
			} else {
	    		$this->command->warn('No se pudo agregar.');
			}
    }
}
