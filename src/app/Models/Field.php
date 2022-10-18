<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Field extends Model
{
    use Uuids, SoftDeletes;
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
    protected $hidden = ['deleted_at', "created_at", "updated_at", "status", "html_data_type", "validation_laravel", "attribute", "order", "api_log", "crew_id"];


    /**
     * Return all hidden fields
     *
     * @return string

    public  function getHiddenValue(){
        return $this->hidden;
    } */


    public function getStatusAttribute($value){
        switch ($value) {
            case 0:
                $text_status = "Disabled";
                break;
            case 1:
                $text_status = "Website";
                break;
            case 2:
                $text_status = "Website & App";
                break;
            default:
                $text_status = "Undefined";
        }
        return $text_status;
    }
    /**
     * Indicate the the status id stored in DB
     *
     * @return String
     */
    public function getStatusId(){
        return $this->attributes['status'];
    }

    public function getRequiredAttribute($value){
        switch ($value) {
            case 0:
                $text_required = "Auto generated";
                break;
            case 1:
                $text_required = "Required";
                break;
            case 2:
                $text_required = "Strongly advised";
                break;
            case 3:
                $text_required = "Advised";
                break;
            case 4:
                $text_required = "If possible";
                break;
            default:
                $text_required = "Undefined";
        }
        return $text_required;
    }

    /**
     * Indicate the the required id stored in DB
     *
     * @return String
     */
    public function getRequiredId(){
        return $this->attributes['required'];
    }

    /**
     * Convert a given requirement description into the associate int
     *
     * @param $value
     * @return int
     */
    public static function convertRequiredAttribute($value){
        switch ($value) {
            case "Auto generated":
                $int_required = 0;
                break;
            case "Required":
                $int_required = 1;
                break;
            case "Strongly advised":
                $int_required = 2;
                break;
            case "Advised":
                $int_required = 3;
                break;
            case "If possible":
                $int_required = 4;
                break;
            default:
                $int_required = 100;
        }
        return $int_required;
    }

    /**
     * It returns the html_data_type according the selected database type value
     *
     * @param String$database_type
     * @return string
     */

    public static function getHtmlDataTypeFromForm(String $database_type){
        $type_convert = [
            "string" => "text",
            "text" => "textarea",
            "integer" => "number",
            "date" => "date",
            "boolean" => "checkbox"
        ];
        return $type_convert[$database_type];
    }

    /**
     * It returns the UI type value according the selected database type value
     *
     * @param String $database_type
     * @return string
     */

    public static function getUITypeFromForm(String $database_type){
        $type_convert = [
            "string" => "EditText",
            "text" => "EditText",
            "integer" => "number",
            "date" => "date",
            "boolean" => "Radio Button"
        ];
        return $type_convert[$database_type];
    }

    /**
     * Generates the laravel validator according the field datas
     *
     * @param $field
     * @return string
     */

    public static function getValidationLaravelFromForm($field){

        $validador = array();
        $type_convert = [
            "string" => "String",
            "text" => "String",
            "integer" => "Integer",
            "date" => "Date",
            "boolean" => "Boolean"
        ];

        if($field["required"] == 1){
            array_push($validador, "required");
        }
        else{
            array_push($validador, "nullable");
        }

        array_push($validador, $type_convert[$field["database_type"]]);

        //TODO : ajouter la gestion des champs spécifiques à la validation laravel

        $laravel_validator = implode("|", $validador);

        return $laravel_validator;
    }

    /**
     *
     * Getter to return correctly the linkedlist name
     * @param $value
     * @return string
     */
    /*
    public function getLinkedListAttribute($value){
        $linked_list =  ListControl::find($value);
        return (empty($linked_list) ? "" : $linked_list->title);
    }
    */
    public function getValue(){
        if(empty(($this->linked_list))) {
            return $this->pivot->value;
        }
        $model = 'App\Models\\' . $this->linkedList->name;
        $id = $this->pivot->value;
        $displayed_value = $this->linkedList->displayed_value;
        return $model::find($id)->$displayed_value;
    }

    public function linkedList(){
        return $this->hasOne(ListControl::class, "id","linked_list");
    }

    /**
     * Get all the element from the linkedlist
     *
     * @param  string  $id
     * @return array
     */
    public function getLinkedListContent()
    {
        $list_control = ListControl::find($this->getLinkedListId());
        $list = $list_control->getListContent()->toArray();
        $displayed_value = $list_control->displayed_value;
        return array_column($list, $displayed_value, "id");
    }

    /**
     * Indicate the the UUID stored in DB
     *
     * @return String
     */
    public function getLinkedListId()
    {
        return $this->attributes['linked_list'];
    }

    public static function getUsedLinkedList()
    {
        $used_linked_list = self::where("linked_list", "!=", "")->get()->toArray();
        return array_column($used_linked_list, "linked_list");
    }

    public function listControl()
    {
        return $this->BelongsTo(ListControl::class, "linked_list", "id");
    }

    /**
     * It returns the list control dataset for API calls
     *
     * @return array
     *
     */
    public static function getAPIContent(User $user)
    {


        $call_class_name = get_called_class();
        $class_name = substr(strrchr($call_class_name, "\\"), 1);
        $database_content = $call_class_name::where('crew_id', $user->crew->id)->where("status", 2)->orderBy("required")->orderBy("order")->get()->toArray();
        $list_info = ListControl::where('name', $class_name)->first();
        $keys = array_column($database_content, $list_info->key_value); // all keys name

        $api_res = array();
        foreach ($keys as $key_index => $key_value) {
            $api_res[$key_value] = $database_content[$key_index];

            $translations = array_column(Translation::where('list', $list_info->id)->where('field_key', $key_value)->get()->toArray(), "translation", "language");
            foreach($translations as $language => $translation){
                $api_res[$key_value]["displayed_value"][$language] = $translation;
            }
            unset($api_res[$key_value][$list_info->key_value]);
            unset($api_res[$key_value][$list_info->displayed_value]);
            $api_res[$key_value]["required"] = Field::convertRequiredAttribute( $database_content[$key_index]["required"]);
        }
        return $api_res;

    }
}
