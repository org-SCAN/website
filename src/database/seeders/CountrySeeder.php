<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Country;
use Illuminate\Support\Str;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $obj_json = file_get_contents(config('jsonDataset.path')."/"."countries".".json");
        // interpret the json format as an array
        $array_json = json_decode($obj_json, true);

        // make the inserts
        foreach($array_json as $country)
        {
            $to_store = array();
            $to_store["id"] = (string)Str::uuid();
            foreach ($country as $countryKey => $countryValue) {
                $to_store[$countryKey] = $countryValue;
            }
            Country::create($to_store);
        }
    }
}
