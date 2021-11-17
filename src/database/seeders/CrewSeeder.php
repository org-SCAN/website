<?php

namespace Database\Seeders;


use App\Models\Crew;
use Illuminate\Database\Seeder;


class CrewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /*
        $obj_json = file_get_contents(config('jsonDataset.path')."/"."crew".".json");
        // interpret the json format as an array
        $array_json = json_decode($obj_json, true);

        // make the inserts
        foreach($array_json as $crew)
        {
            $to_store = array();
            foreach ($crew as $routeKey => $routeValue) {
                $to_store[$routeKey] = $routeValue;
            }
            Crew::create($to_store);
        }
        */

        Crew::create(["name" => env("DEFAULT_TEAM")]);
    }
}
