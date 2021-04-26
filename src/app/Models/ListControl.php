<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Model;

class ListControl extends Model
{
    use Uuids;
    /**
     * The data type of the auto-incrementing ID.
     *
     * @var string
     */
    protected $keyType = 'string';

    /**
     * Indicates if the model's ID is auto-incrementing.
     *
     * @var bool
     */

    public $incrementing = false;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['deleted',"created_at","updated_at", "id"];

    public function addNewList(){
        // 1. Create a new table -> for the list

        // 2. Create a new table -> for the list column name
    }

    public function getListContent(){

        $model = 'App\Models\\'.$this->name;
        $list = $model::orderBy($this->displayed_value)
            ->get()
            ->makeVisible("id")
            ->toArray();

        return $list;
    }

    /**
     * It returns the list control dataset for API calls
     *
     * @return array
     *
     */
    public static function getAPIContent(){
        $call_class_name = get_called_class();
        $class_name = substr(strrchr($call_class_name, "\\"), 1);
        $database_content = $call_class_name::where('deleted', 0)->get()->toArray();
        $list_info = ListControl::where('name', $class_name)->first();
        $keys = array_column($database_content, $list_info->key_value); // all keys name
        $api_res = array();
        foreach ($keys as $key_index => $key_value){
            $api_res[$key_value] = $database_content[$key_index];

            $translations = array_column(Translation::where('deleted',0)->where('list', $list_info->id)->where('field_key', $key_value)->get()->toArray(), "translation", "language");
            foreach($translations as $language => $translation){
                $api_res[$key_value]["displayed_value"][$language] = $translation;
            }
            unset($api_res[$key_value][$list_info->key_value]);
            unset($api_res[$key_value][$list_info->displayed_value]);
        }
        return $api_res;
    }
}
