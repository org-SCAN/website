<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class LinkSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Link::factory()
            ->count(40)
            ->create();
    }
}
