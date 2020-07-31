<?php

use Illuminate\Database\Seeder;

class UsersPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\UserPermission::class, 1)->create();

    }
}
