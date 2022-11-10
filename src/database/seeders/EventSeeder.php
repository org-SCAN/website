<?php

namespace Database\Seeders;

use App\Models\Event;
use Illuminate\Database\Seeder;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $results = Event::factory()
            ->count(5)
            ->create();
        // Foreach results, create a translation
        $listControl = \App\Models\ListControl::where("name", "Event")->first();
        foreach ($results as $result) {
            \App\Models\Translation::handleTranslation($listControl, $result->{$listControl->key_value}, $result->{$listControl->displayed_value}, \App\Models\Language::defaultLanguage()->id);
        }
    }
}
