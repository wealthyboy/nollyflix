<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\UserPermission;
use Faker\Generator as Faker;
use App\User;
use App\Permission;

$factory->define(UserPermission::class, function (Faker $faker) {
    return [
        'user_id' => 1,
        'permission_id' => 1
    ];
});
