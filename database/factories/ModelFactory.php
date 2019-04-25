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
use Faker\Generator as Faker;
$factory->define(App\User::class, function (Faker $faker) {
    static $password;

    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
    ];
});

$factory->define(App\Post::class, function(Faker $faker){
    return [
        'title' => $faker->sentences(1),
        'content' => $faker->paragraphs(10),
        'created_at'=> $faker->dateTimeBetween($startDate = '-30 days', $endDate = 'now'),
        'updated_at'=> $faker->dateTimeBetween($startDate = '-1 days', $endDate = 'now'),
    ];
});
