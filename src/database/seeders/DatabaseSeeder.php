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
        $this->call(PermissionSeeder::class);
        $this->call(RoleSeeder::class);
        $this->call(CrewSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(LanguageSeeder::class);
        $this->call(ListControlSeeder::class);
        $this->call(FieldSeeder::class);
        $this->call(ListCountrySeeder::class);
        $this->call(ListRoleSeeder::class);
        $this->call(ListRouteSeeder::class);
        $this->call(ListGenderSeeder::class);
        $this->call(ListRelationTypeSeeder::class);
        $this->call(ListRelationSeeder::class);
        $this->call(ListEventTypeSeeder::class);
        $this->call(ListSourceTypeSeeder::class);
        if (env('APP_DEBUG') && env('APP_ENV') == 'local') {
            $this->call(EventSeeder::class);
            $this->call(SourceSeeder::class);
            $this->call(RefugeeSeeder::class);
            $this->call(LinkSeeder::class);
            $this->call(PlaceSeeder::class);
        }
    }
}
