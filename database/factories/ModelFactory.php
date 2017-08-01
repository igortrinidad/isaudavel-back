<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\Models\Place;
use App\Models\PlaceCategory;
use App\Models\User;

$faker = \Faker\Factory::create('pt_BR');

$factory->define(App\Models\Professional::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'name' => $faker->firstName,
        'last_name' => $faker->lastName,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('password'),
        'remember_token' => str_random(10),
    ];
});



$factory->define(App\Models\Client::class, function () use($faker){
    static $password;

    return [
        'name' => $faker->firstName,
        'last_name' => $faker->lastName,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('password'),
        'phone' => $faker->cellphoneNumber,
        'remember_token' => str_random(10),
    ];
});

$factory->define(App\Models\OracleUser::class, function () use($faker){
    static $password;

    return [
        'name' => $faker->firstName,
        'last_name' => $faker->lastName,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('password'),
        'remember_token' => str_random(10),
    ];
});


