<?php

namespace Database\Seeders;

use App\Models\Crew;
use App\Models\Field;
use App\Models\ListControl;
use App\Models\ListDataType;

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
        // I have to fake the structure, by adding at least the displayed value to the list control,
        $struc = $this->list->structure()->create(["field" => $this->displayed_value]);
        $this->list->update(['displayed_value' => $struc->id]);


        foreach ($this->array_json as $fields) {

            $to_store = array();
            foreach ($fields as $keyField => $fieldValue) {
                //If the key is the displayed value, we have to store it in translation
                if ($keyField == $this->list->displayed_value) {
                    $fieldValue = $fieldValue[$this->default_language];
                }
                // There is a special condition for linked_list
                if ($keyField == "linked_list" && !empty($fieldValue)) {
                    $fieldValue = ListControl::firstWhere("name", ucfirst($fieldValue))->id;
                }
                // There is a special condition for data_type
                if ($keyField == "data_type_id" && !empty($fieldValue)) {
                    $fieldValue = ListDataType::firstWhere("name", ucfirst($fieldValue))->id;
                }
                $to_store[$keyField] = $fieldValue;
            }
            $to_store["crew_id"] = Crew::getDefaultCrewId();
            $field = Field::create($to_store);


            $this->storeTranslation($fields[$this->displayed_value], $field->{$this->list_field_key});
        }
    }
}
