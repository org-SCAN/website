<?php

namespace Database\Seeders;

use App\Models\Language;
use App\Models\ListControl;
use App\Models\Translation;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class GlobalListControlSeeder extends Seeder
{
    protected $list_name;
    protected $class_name; //class_name must be define in the child class.
    protected $array_json;
    protected $displayed_value;
    protected $list_id;
    protected $languages;
    protected $default_language;
    protected $list_field_key;

    /**
     * GlobalListControlSeeder constructor.
     * @param $list_name
     */
    public function __construct($list_name)
    {
        $this->list_name = $list_name;
        $this->class_name = Str::ucfirst(Str::singular($list_name));
        $this->languages = array_column(Language::all()->toArray(), "id", "language");
        $this->default_language = Language::where("default", 1)->first()->language;
        $this->getJsonDatas();
        $this->getListInfo();
    }

    /**
     * Initialize the json_array from json file
     *
     * @param $json_name
     */

    protected function getJsonDatas(){
        $obj_json= file_get_contents(config('jsonDataset.path')."/".Str::plural(Str::lower($this->list_name)).".json");
        $this->array_json=json_decode($obj_json, true);
    }

    protected function getListInfo(){
        $list = ListControl::where('name', $this->list_name)->first();
        $this->displayed_value = $list->displayed_value;
        $this->list_field_key = $list->key_value;
        $this->list_id = $list->id;
    }
    protected function storeTranslation($displayed_value, $field_key){
        foreach ($displayed_value as $language => $value) {
            //check that the language exists in the language DB
            if (key_exists($language, $this->languages)) {
                $translation = array();
                $translation["language"] = $this->languages[$language];
                $translation["list"] = $this->list_id;
                $translation["field_key"] = $field_key;
                $translation["translation"] = $value;
                //add the translation in the table
                Translation::create($translation);
            }
        }
        //set the value as the one for the default language
        return $displayed_value[$this->default_language];
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->array_json as $json_elem) {
            $to_store = array();
            // get all the content from the json and store it in the correct DB
            foreach ($json_elem as $key => $value) {
                //If the key is the displayed value, we have to store it in translation
                if ($key == $this->displayed_value) {
                    $value = $this->storeTranslation($value, $json_elem[$this->list_field_key]);
                }
                $to_store[$key] = $value;
            }
            $model = 'App\Models\\' . $this->class_name;
            $model::create($to_store);
        }
    }
}
