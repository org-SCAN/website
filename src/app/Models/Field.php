<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuids;

class Field extends Model
{
    use HasFactory, Uuids;
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
     * Get all of the owning linked_list models.
     */
    public function get_linked_list()
    {
        return $this->morphTo();
    }

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
     * It returns the html_data_type according the selected database type value
     *
     * @param String$database_type
     * @return string
     */

    public static function getHtmlDataTypeFromForm(String $database_type){
        $type_convert = [
            "string" => "text",
            "int" => "number",
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
            "int" => "number",
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
            "int" => "Integer",
            "date" => "Date",
            "boolean" => "Boolean"
        ];

        if($field->required == 1){
            array_push($validador, "required");
        }

        array_push($validador, $type_convert[$field->database_type]);

        //TODO : ajouter la gestion des champs spécifiques à la validation laravel

        $laravel_validator = implode("|", $validador);

        return $laravel_validator;
    }
}
