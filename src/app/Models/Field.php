<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Field extends Model
{
    use Uuids, SoftDeletes, hasFactory;


    /**
     * The requirement of the field.
     *
     * @var array
     */
    public static $requiredTypes = [
        2 => "Strongly advised",
        3 => "Advised",
        4 => "If possible",
        100 => "Undefined"
    ];

    /**
     * The platform where the attribute is available.
     *
     * @var array
     */
    public static $statusTypes = [
        0 => "Disabled",
        1 => "Website",
        2 => "Website & App"
    ];
    /**
     * Indicates if the model's ID is auto-incrementing.
     *
     * @var bool
     */

    public $incrementing = false;
    /**
     * The data type of the auto-incrementing ID.
     *
     * @var string
     */
    protected $keyType = 'string';
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


    public static function getUsedLinkedList()
    {
        $used_linked_list = self::where("linked_list", "!=", "")->get()->toArray();
        return array_column($used_linked_list, "linked_list");
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
     * This function returns true if the best_descriptive_value is defined, false otherwise
     * @return bool
     */
    public static function hasBestDescriptiveValue() {
         return self::where('crew_id', Auth::user()->crew->id)->where('best_descriptive_value', 1)->exists();
    }

    public function getStatusAttribute($value)
    {
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

    public function listControl()
    {
        return $this->BelongsTo(ListControl::class, "linked_list", "id");
    }

    /**
     * Field hasOne data type
     */
    public function dataType(){
        return $this->hasOne(ListDataType::class, "id", "data_type_id");
    }
}
