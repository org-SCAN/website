<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;


class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        if(User::where("email", env("DEFAULT_EMAIL"))->get()->isEmpty()){
            User::createDefaultUser();
        }

    }
}
