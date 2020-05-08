<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Order;
use Faker\Generator as Faker;

$factory->define(Order::class, function (Faker $faker) {
    $newUser = factory(App\User::class)->create();
    return [
        'user_id' => $newUser->id,
        'order_num' => strtoupper(substr(sha1(uniqid()), 0, 12)),
        'product_name' => $faker->sentence,
    ];
});
