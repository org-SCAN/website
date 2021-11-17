<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        $this->call(UserRoleSeeder::class);
        $this->call(CrewSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(ListControlSeeder::class);
        $this->call(LanguageSeeder::class);
        $this->call(FieldSeeder::class);
        $this->call(CountrySeeder::class);
        $this->call(RoleSeeder::class);
        $this->call(RouteSeeder::class);
        $this->call(GenderSeeder::class);
        $this->call(RelationSeeder::class);
        if (env('APP_DEBUG')) {
            $this->call(RefugeeSeeder::class);
            $this->call(LinkSeeder::class);
        }
    }
}
