<?php

namespace Database\Seeders;

use App\Models\ListControl;
use App\Models\ListDataType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ListControlSeeder extends Seeder
{

    /**
     * The constructor get the required fields
     */
    public function __construct(){
        $this->json_file = json_decode(file_get_contents(config('jsonDataset.path')."/config/"."lists_controls".".json"), true);
    }

    /**
     * Run the database seeds.
     * This function seeds the list control table (where a list is defined as a list)
     *
     * @return void
     */
    public function run()
    {
        // make the inserts
        foreach($this->json_file as $fields)
        {
            $fields["id"] = (string)Str::uuid();
            unset($fields["structure"]);
            ListControl::create($fields);
        }


        // Fill the ListDataType table
        $this->call([
            ListDataTypeSeeder::class,
        ]);

        // store the list structure
        $this->storeStructure();
    }

    public function storeStructure(){
        foreach(ListControl::all() as $list){
            foreach($this->json_file[$list->name]["structure"] as $field){
                if($field["data_type_id"] == "List" && isset($field["associated_list"])){
                    $associated_list = ListControl::getListFromLinkedListName($field["associated_list"]); // the list that is associated with the current list
                    unset($field["associated_list"]);
                }
                //if($list->name != "ListDataType"){
                    // Convert the data_type_id to the data_type_id of the data_type table
                    $field["data_type_id"] = ListDataType::firstWhere('name', $field["data_type_id"])->id;
                //}

                // Store the structure of the list
                $structure = $list->structure()->firstOrCreate($field);

                // Associate the list element with the associated list
                if(isset($associated_list)){
                    $structure->list()->attach([$associated_list->id]);
                }

                if($field['field'] == $list->displayed_value){
                    $list->update(["displayed_value" => $structure->id]);
                }
            }

        }
    }
}
