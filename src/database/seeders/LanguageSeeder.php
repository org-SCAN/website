<?php

namespace Database\Seeders;

use App\Models\Language;
use Illuminate\Database\Seeder;

class LanguageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $obj_json = file_get_contents(config('jsonDataset.path')."/"."languages".".json");
        // interpret the json format as an array
        $array_json = json_decode($obj_json, true);

        // make the inserts
        foreach($array_json as $language)
        {
            $to_store = array();
            foreach ($language as $languageKey => $languageValue) {
                $to_store[$languageKey] = $languageValue;
            }
            Language::create($to_store);
        }
    }
}
