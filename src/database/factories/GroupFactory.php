<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Group;
use Faker\Generator as Faker;
use App\User;

$factory->define(Group::class, function (Faker $faker) {
    return [
        'owner_id' => factory(User::class)->create()->id,
        'name' => $faker->sentence(1, 6)
    ];
});
