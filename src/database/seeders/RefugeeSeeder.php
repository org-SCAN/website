<?php

namespace Database\Seeders;

use App\Models\FieldRefugee;
use App\Models\Refugee;
use Illuminate\Database\Seeder;

class RefugeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        $refugees = Refugee::factory()->count(20)->create();

        foreach ($refugees as $refugee) {
            $refugee->fields()->attach(FieldRefugee::random_fields());
        }

    }
}
