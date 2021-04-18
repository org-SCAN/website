<?php

namespace Database\Seeders;

use App\Models\Country;
use App\Models\ListControl;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(ListControlSeeder::class);
        $this->call(FieldSeeder::class);
        $this->call(CountrySeeder::class);
        $this->call(RoleSeeder::class);
        $this->call(RouteSeeder::class);
        $this->call(GenderSeeder::class);
        $this->call(RefugeeSeeder::class);
        $this->call(LinkSeeder::class);
    }
}
