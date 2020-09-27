<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\User;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

$factory->define(User::class, function (Faker $faker) {
    $email = $faker->unique()->safeEmail;
    $pseudo = explode('@', $email)[0];
    return [
        'name' => $faker->name(),
        'first_name' => $faker->firstName(),
        'email' => $email,
        'pseudo' => $pseudo,
        'created_at' => now(),
        'updated_at' => now(),
        'role_id' => $faker->numberBetween($min = 1, $max = 4),
        'fk_lieu_id' => $faker->numberBetween($min = 1, $max = 3),
        'password' => hash ('tiger192,3' , $faker->unique()->name()), // password
        //'remember_token' => Str::random(10),
    ];
});
