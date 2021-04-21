<?php

namespace Database\Seeders;


use App\Models\UserRole;
use Illuminate\Database\Seeder;


class UserRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $obj_json = file_get_contents(config('jsonDataset.path')."/"."user_roles".".json");
        // interpret the json format as an array
        $array_json = json_decode($obj_json, true);

        // make the inserts
        foreach($array_json as $route)
        {
            $to_store = array();
            foreach ($route as $routeKey => $routeValue) {
                $to_store[$routeKey] = $routeValue;
            }
            UserRole::create($to_store);
        }
    }
}
