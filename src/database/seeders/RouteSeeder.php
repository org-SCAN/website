<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Route;
use Illuminate\Support\Str;

class RouteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $obj_json = file_get_contents(config('jsonDataset.path')."/"."routes".".json");
        // interpret the json format as an array
        $array_json = json_decode($obj_json, true);

        // make the inserts
        foreach($array_json as $route)
        {
            $to_store = array();
            $to_store["id"] = (string)Str::uuid();
            foreach ($route as $routeKey => $routeValue) {
                $to_store[$routeKey] = $routeValue;
            }
            Route::create($to_store);
        }
    }
}
