<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Task;
use Faker\Generator as Faker;
use App\Group;
use App\User;

$factory->define(Task::class, function (Faker $faker) {
    return [
        'group_id' => factory(Group::class)->create()->id,
        'owner_id' => factory(User::class)->create()->id,
        'body' => $faker->sentence(1, 6)
    ];
});
