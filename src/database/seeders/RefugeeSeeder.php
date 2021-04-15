<?php

namespace Database\Seeders;

use App\Models\Refugee;
use Illuminate\Database\Seeder;

class RefugeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Refugee::factory()
            ->count(50)
            ->create();
    }
}
