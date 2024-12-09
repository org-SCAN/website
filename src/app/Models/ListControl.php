<?php

namespace App\Models;

use App\Traits\ModelEventsLogs;
use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;


class ListControl extends Model
{
    use Uuids, SoftDeletes, hasFactory, ModelEventsLogs;

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
    protected $hidden = [
        'deleted_at',
        "created_at",
        "updated_at",
    ];

    /**
     *
     * It returns the content of the displayed value of the control list
     * @param $id
     * @return string
     */

    public static function getDisplayedValueContent($id) {
        $call_class_name = get_called_class();
        $class_name = substr(strrchr($call_class_name,
            "\\"),
            1); //get the name of the class : eg ListCountry / ListGender / …

        $displayed_value = ListControl::where('name',
            $class_name)->first()->displayed_value;
        $displayed_value_content = $call_class_name::find($id);

        return empty($displayed_value_content) ? "" : $displayed_value_content->$displayed_value; //the content of the displayed value
    }

    public static function getIdFromValue($value) {
        $call_class_name = get_called_class();
        $class_name = substr(strrchr($call_class_name,
            "\\"),
            1); //get the name of the class : eg ListCountry / ListGender / …
        if (Str::isUuid($value)) {
            $val = $call_class_name::find($value);
            if (!empty($val)) {
                return $value;
            }
        } else {
            $key_value = ListControl::where("name",
                $class_name)->first()->key_value;
            $val = $call_class_name::where($key_value,
                $value)->first();
            if (!empty($val)) {
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
    public static function getAPIContent(User $user) {
        $call_class_name = get_called_class();
        $class_name = substr(strrchr($call_class_name,
            "\\"),
            1); //get the name of the class : eg ListCountry / ListGender / …

        $database_content = $call_class_name::all()->toArray();
        $list_info = ListControl::where('name',
            $class_name)->first();
        $keys = array_column($database_content,
            $list_info->key_value); // all keys name
        $api_res = [];
        foreach ($keys as $key_index => $key_value) {
            $api_res[$key_value] = $database_content[$key_index];

            $translations = array_column(Translation::where('list',
                $list_info->id)->where('field_key',
                $key_value)->get()->toArray(),
                "translation",
                "language");
            foreach ($translations as $language => $translation) {
                $api_res[$key_value]["displayed_value"][$language] = $translation;
            }
            unset($api_res[$key_value][$list_info->key_value]);
            unset($api_res[$key_value][$list_info->displayed_value]);
        }
        return $api_res;
    }

    public static function list() {
        // order by displayed value and pluck it
        $displayed_value = self::getDisplayedValue();

        return self::orderBy($displayed_value)->pluck($displayed_value,
            'id');
    }


    public static function getDisplayedValue() {
        $call_class_name = get_called_class();
        $class_name = substr(strrchr($call_class_name,
            "\\"),
            1); //get the name of the class : eg ListCountry / ListGender / …

        return ListControl::where('name',
            $class_name)->first()->displayed_value;
    }

    /**
     * This function get the list from the list name
     */
    public static function getListFromLinkedListName($list_name) {
        return ListControl::where('name',
                Str::ucfirst($list_name))->first();
    }

    /**
     * This function get the list from the list id
     */
    public static function getListFromLinkedListId($id) {
        return "App\Models\\".ListControl::find($id);
    }

    /**
     * This function get a list element from :
     * - the list id
     * - the element id
     *
     * @param  string  $list_id
     * @param  string  $element_id
     */
    public static function getListElementFromId($list_id,
        $element_id) {
        $list = ListControl::find($list_id);
        $model = 'App\Models\\'.$list->name;
        return $model::find($element_id);
    }

    public function fields() {
        return $this->hasMany(Field::class,
            "linked_list");
    }

    public function list_content() {
        $model = 'App\Models\\'.$this->name.'::class';
        return $this->hasMany($model);
    }

    public function crews() {
        return $this->belongsToMany(Crew::class,
            'fields',
            'linked_list');
    }

    public function getListDisplayedValue() {
        return $this->getListContent()->pluck($this->displayed_value, $this->key_value);
    }

    public function getListContent() {

        $model = 'App\Models\\'.$this->name;
        $list = $model::orderBy($this->displayed_value)->get();

        return $list;
    }

    /**
     * This function returns the fields that describes the list (± the table columns)
     * e.g : Country is described by ISO2, ISO3, full_name
     */
    public function getListFields() {
        $model = 'App\Models\\'.$this->name;
        $list = $model::first();

        return array_keys($list->makeHidden('id')->toArray());
    }

    public function getDisplayedValueAttribute() {
        return $this->structure()->find($this->attributes['displayed_value'])->field ?? $this->attributes['displayed_value'];
    }

    public function structure() {
        return $this->hasMany(ListStructure::class);
    }


    /**
     *
     * TODO : rename this function when using the associative table with fields
     * @return void
     */
    public function associatedStructureFields(){
        return $this->belongsToMany(ListStructure::class, 'associated_lists', 'list_id', 'field_id')
            ->withTimestamps()
            ->withPivot("id");
    }

    public function getDisplayedValueContentAttribute() {
        return $this->{self::getDisplayedValueFromListName()}; //returns the name
    }

    /**
     * This function returns the displayed value of the list from the list name
     */
    public static function getDisplayedValueFromListName() {
        return ListControl::firstWhere('name',
            substr(strrchr(get_called_class(),
                "\\"),
                1))->displayed_value;
    }

    /**
     * This function finds the element in the list, based on field, value
     * @param string $field
     * @param string $value
     *
     * @return object | null
     */

    public function findElement($field,$value) {

        $model = 'App\Models\\'.$this->name;
        return $model::where($field,
            $value)->first();
    }
}
