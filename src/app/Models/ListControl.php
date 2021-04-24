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


    public function addNewList(){
        // 1. Create a new table -> for the list

        // 2. Create a new table -> for the list column name
    }

    public function getListContent(){

        $model = 'App\Models\\'.$this->name;
        $list = $model::orderBy($this->displayed_value)
            ->get()
            ->toArray();

        return $list;
    }

    /**
     * It returns the list control dataset for API calls
     *
     * @return array
     *
     */
    public function getAPIContent(){
        $class_name = class_name($this);
        $database_content = $class_name::where('deleted', 0)->get()->toArray();
        $list_info = ListControl::where('name', $class_name)->first();

        $keys = array_column($database_content, $list_info->key); // all keys name
        $api_res = array();
        foreach ($keys as $key_index => $key_value){
            $res = array();
            $res[$key_value]["displayed_value"] = array_column(Translation::where('deleted',0)->where('list', $list_info->id)->where('field_key', $key_value)->get()->toArray(), "translation", "language");
            $res[$key_value] = $database_content[$key_index];
            unset($res[$key_value][$list_info->key]);
            unset($res[$key_value][$list_info->displayed_value]);
            array_push($api_res, $res);
        }
        return $api_res;
    }
}
