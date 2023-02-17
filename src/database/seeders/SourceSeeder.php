<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class SourceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $results = \App\Models\Source::factory()->count(10)->create();
        // Foreach results, create a translation
        $listControl = \App\Models\ListControl::where("name", "Source")->first();
        foreach ($results as $result) {
            \App\Models\Translation::handleTranslation($listControl, $result->{$listControl->key_value}, $result->{$listControl->displayed_value}, \App\Models\Language::defaultLanguage()->id);
        }
    }
}
