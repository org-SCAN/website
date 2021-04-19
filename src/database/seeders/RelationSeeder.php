<?php

namespace Database\Seeders;

use App\Models\Relation;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class RelationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $obj_json = file_get_contents(config('jsonDataset.path')."/"."relations".".json");
        // interpret the json format as an array
        $array_json = json_decode($obj_json, true);

        // make the inserts
        foreach($array_json as $role)
        {
            $to_store = array();
            foreach ($role as $roleKey => $roleValue) {
                $to_store[$roleKey] = $roleValue;
            }
            Relation::create($to_store);
        }
    }
}
