<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use Mnemosine\Research;
use Mnemosine\Model;
use Faker\Generator as Faker;


$factory->define(Research::class, function (Faker $faker) {
    return [
        'title' => $faker->title,
        'author_ids' => rand(489,1703),
        'set_id' => rand(1701, 1702),
        'technique' => $faker->text,
        'materials' => $faker->text,
        'period_id' => rand(1183,1324),
        'place_of_creation_id' => rand(1325,1688),
        'firm' => rand(0,1),
        'firm_description' => $faker->text,
        'short_description' => $faker->text,
        'formal_description' => $faker->text,
        'observation' => $faker->text,
        'publications' => $faker->text,
        'piece_id' => rand(1, 2000),
        'deleted_by' => rand(1,3),
        'created_by' => rand(1,3),
        'updated_by' => rand(1,3), 
    ];
});
