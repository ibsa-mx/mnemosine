<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use Mnemosine\Piece;
use Mnemosine\Model;
use Faker\Generator as Faker;

$factory->define(Piece::class, function (Faker $faker) {
    $arrayValues = ['base', 'frame'];
    return [
        'inventory_number' => $faker->unique()->sentence(8),
        'origin_number' => $faker->word(6),
        'catalog_number' => $faker->word(6),
        'appraisal' => rand(0.00, 10000000.00),
        'description_origin' => $faker->text,
        'gender_id' => rand(1,32),
        'subgender_id' => rand(1,134),
        'type_object_id' => rand(1,403),
        'location_id' => rand(404,478),
        'tags' => $faker->sentence,
        'height' => rand(1,200),
        'width' => rand(1,200),
        'depth' => rand(1,200),
        'diameter' => rand(1,200),
        'height_with_base' => rand(1,200),
        'width_with_base' => rand(1,200),
        'depth_with_base' => rand(1,200),
        'diameter_with_base' => rand(1,200),
        'base_or_frame' => $arrayValues[rand(0,1)],
        'research_info' => rand(0,1),
        'restoration_info' => rand(0,1),
        'craeted_by' => rand(1,3),
    ];
});
