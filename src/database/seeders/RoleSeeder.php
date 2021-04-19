<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use Illuminate\Support\Str;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $obj_json = file_get_contents(config('jsonDataset.path')."/"."roles".".json");
        // interpret the json format as an array
        $array_json = json_decode($obj_json, true);

        // make the inserts
        foreach($array_json as $role)
        {
            $to_store = array();
            $to_store["id"] = (string)Str::uuid();
            foreach ($role as $roleKey => $roleValue) {
                $to_store[$roleKey] = $roleValue;
            }
            Role::create($to_store);
        }
    }
}
