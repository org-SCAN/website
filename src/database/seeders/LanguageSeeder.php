<?php

namespace Database\Seeders;

use App\Models\Gender;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

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
            $to_store["id"] = (string)Str::uuid();
            foreach ($language as $languageKey => $languageValue) {
                $to_store[$languageKey] = $languageValue;
            }
            Gender::create($to_store);
        }
    }
}
