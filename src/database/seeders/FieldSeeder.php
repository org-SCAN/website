<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Field;
use App\Models\ListControl;
use Illuminate\Support\Str;

class FieldSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $obj_json = file_get_contents(config('jsonDataset.path')."/"."fields".".json");
        // interpret the json format as an array
        $array_json = json_decode($obj_json, true);
        // make the inserts
        foreach($array_json as $fields)
        {
            $to_store = array();
            $to_store["id"] = (string)Str::uuid();
            foreach ($fields as $keyField => $fieldValue) {
                if($keyField == "linked_list" && !empty($fieldValue)){
                    $fieldValue = ListControl::where("name", ucfirst($fieldValue))->first()->id;
                }
                $to_store[$keyField] = $fieldValue;
            }
            Field::create($to_store);
        }
    }
}
