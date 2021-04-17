<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuids;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;

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
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];


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
     * It returns the html_data_type according the selected database type value
     *
     * @param String$database_type
     * @return string
     */

    public static function getHtmlDataTypeFromForm(String $database_type){
        $type_convert = [
            "string" => "text",
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
            "integer" => "Integer",
            "date" => "Date",
            "boolean" => "Boolean"
        ];

        if($field["required"] == 1){
            array_push($validador, "required");
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
    public function getLinkedListAttribute($value){
        $linked_list = ListControl::find($value);
        return (empty($linked_list) ? "" : $linked_list->title);
    }

    /**
     * Get all the element from the linkedlist
     *
     * @param  string  $id
     * @return array
     */
    public function getLinkedListContent()
    {
        //
        //abort_if(Gate::denies('manage_refugees_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $model = ListControl::find($this->getLinkedListId());
        $model = 'App\Models\\'.$model->name;
        $displayed_value = ListControl::find($this->getLinkedListId())->displayed_value;
        $list = $model::orderBy($displayed_value)
            ->get()
            ->toArray();

        return array_column($list, $displayed_value, "id");
    }


    /**
     * Indicate the the UUID stored in DB
     *
     * @return String
     */
    public function getLinkedListId(){
        return $this->attributes['linked_list'];
    }

    public function addFieldtoRefugees(){
        $table_name = "refugees";

        $database_type = $this->attributes['database_type'];
        $column_name = $this->attributes['label'];
        $migration_name = "add_".$column_name."_to_refugees";
        $migration_dir=config('database.migration_path');

        $classname = Str::camel($migration_name); // used in the ob_get_content
        $schema = $database_type."('".$column_name."')"."->nullable()"; // used in the ob_get_content

        ob_start();
        include($migration_dir."/default/default_update_structure.php");
        $new_file_content = ob_get_contents();
        ob_get_clean();
        $date = date("Y_m_d_His");
        $file_name = $date."_".$migration_name.".php";
        $new_file_content = "<?php
        ".$new_file_content;
        file_put_contents($migration_dir."/".$file_name, $new_file_content);
        Artisan::call("migrate");
    }
}
