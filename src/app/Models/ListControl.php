<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

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
    protected $hidden = ['deleted',"created_at","updated_at"];

    public function addNewList(){
        // 1. Create a new table -> for the list

        // 2. Create a new table -> for the list column name
    }

    public function getListContent()
    {

        $model = 'App\Models\\' . $this->name;
        $list = $model::orderBy($this->displayed_value)
            ->get()
            ->toArray();

        return $list;
    }

    public static function getDisplayedValue()
    {
        $call_class_name = get_called_class();
        $class_name = substr(strrchr($call_class_name, "\\"), 1); //get the name of the class : eg Country / Gender / …

        return ListControl::where('name', $class_name)->first()->displayed_value;
    }

    /**
     *
     * It returns the content of the displayed value of the control list
     * @param $id
     * @return string
     */

    public static function getDisplayedValueContent($id)
    {
        $call_class_name = get_called_class();
        $class_name = substr(strrchr($call_class_name, "\\"), 1); //get the name of the class : eg Country / Gender / …

        $displayed_value = ListControl::where('name', $class_name)->first()->displayed_value;
        $displayed_value_content = $call_class_name::find($id);

        return empty($displayed_value_content) ? "" : $displayed_value_content->$displayed_value; //the content of the displayed value
    }

    public static function getIdFromValue($value){
        $call_class_name = get_called_class();
        $class_name = substr(strrchr($call_class_name, "\\"), 1); //get the name of the class : eg Country / Gender / …
        if(Str::isUuid($value)){
            $val = $call_class_name::find($value);
            if(!empty($val)){
                return $value;
            }
        }
        else{
            $key_value = ListControl::where("name", $class_name)->first()->key_value;
            $val = $call_class_name::where($key_value ,$value)->first();
            if(!empty($val)){
                return $val->id;
            }
        }
    }


    /**
     * It returns the list control dataset for API calls
     *
     * @return array
     *
     */
    public static function getAPIContent(){
        $call_class_name = get_called_class();
        $class_name = substr(strrchr($call_class_name, "\\"), 1); //get the name of the class : eg Country / Gender / …

        $database_content = $call_class_name::where('deleted', 0)->get()->makeHidden("id")->toArray();
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
