<?php

namespace Database\Seeders;

use App\Models\ListControl;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ListControlSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $obj_json = file_get_contents(config('jsonDataset.path')."/config/"."lists_controls".".json");
        // interpret the json format as an array
        $array_json = json_decode($obj_json, true);
        // make the inserts
        foreach($array_json as $fields)
        {
            $to_store = array();
            $to_store["id"] = (string)Str::uuid();
            foreach ($fields as $keyField => $fieldValue) {
                if ($keyField != 'structure') {
                    $to_store[$keyField] = $fieldValue;
                }
            }
            ListControl::create($to_store);
        }
    }
}
