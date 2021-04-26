<?php

namespace Database\Seeders;

use App\Models\Field;
use App\Models\ListControl;

class FieldSeeder extends GlobalListControlSeeder
{
    public function __construct()
    {
        parent::__construct("Field");
    }

    /**
     * Run the database seeds
     *
     * @return void
     */
    public function run()
    {
        foreach($this->array_json as $fields)
        {
            $to_store = array();
            foreach ($fields as $keyField => $fieldValue) {
                //If the key is the displayed value, we have to store it in translation
                if ($keyField == $this->displayed_value) {
                    $fieldValue = $this->storeTranslation($fieldValue, $fields[$this->list_field_key]);
                }

                // There is a special condition for linked_list
                if($keyField == "linked_list" && !empty($fieldValue)){
                    $fieldValue = ListControl::where("name", ucfirst($fieldValue))->first()->id;
                }
                $to_store[$keyField] = $fieldValue;
            }
            Field::create($to_store);
        }
    }
}
