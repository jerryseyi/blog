<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Ramsey\Uuid\Uuid;
use Faker\Generator as Faker;
use App\Notifications\ThreadWasUpdated;
use Illuminate\Notifications\DatabaseNotification;

$factory->define(DatabaseNotification::class, function (Faker $faker) {
    return [
        'id' => Uuid::uuid4()->toString(),
        'type' => ThreadWasUpdated::class,
        'notifiable_id' => function () {
            return auth()->id() ?: factory('App\User')->create()->id;
        },
        'notifiable_type' => 'App\User',
        'data' => ['foo' => 'bar']
    ];
});
