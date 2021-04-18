<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Gender;
use Illuminate\Support\Str;

class GenderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $obj_json = file_get_contents(config('jsonDataset.path')."/"."genders".".json");
        // interpret the json format as an array
        $array_json = json_decode($obj_json, true);

        // make the inserts
        foreach($array_json as $gender)
        {
            $to_store = array();
            $to_store["id"] = (string)Str::uuid();
            foreach ($gender as $genderKey => $genderValue) {
                $to_store[$genderKey] = $genderValue;
            }
            Gender::create($to_store);
        }
    }
}
