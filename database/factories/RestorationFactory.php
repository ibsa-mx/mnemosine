<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use Mnemosine\Restoration;
use Mnemosine\Model;
use Faker\Generator as Faker;

$factory->define(Restoration::class, function (Faker $faker) {
    $arrayValues = ['base', 'frame'];
    return [
        'preliminary_examination' => $faker->text,
        'laboratory_analysis' => $faker->text,
        'proposal_of_treatment' => $faker->text,
        'treatment_description' => $faker->text,
        'results' => $faker->text,
        'observations' => $faker->text,
        'responsible_restorer' => rand(1,3),
        'piece_id' => rand(1,3),
        'height' => rand(1,200),
        'width' => rand(1,200),
        'depth' => rand(1,200),
        'diameter' => rand(1,200),
        'height_with_base' => rand(1,200),
        'width_with_base' => rand(1,200),
        'depth_with_base' => rand(1,200),
        'diameter_with_base' => rand(1,200),
        'base_or_frame' => $arrayValues[rand(0,1)],
        'deleted_by' => rand(1,3),
        'created_by' => rand(1,3),
        'updated_by' => rand(1,3),
    ];
});
