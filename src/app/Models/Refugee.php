<?php

namespace App\Models;

use App\Traits\Uuids;
use http\Env\Response;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class Refugee extends Model
{
    use HasFactory, Uuids, SoftDeletes;


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

    public static function getAllBestDescriptiveValues() {
        $best_descriptive_values = [];
        foreach (self::all() as $elem) {
            if ($elem->crew->id == Auth::user()->crew->id) {
                $best_descriptive_values[$elem->id] = $elem->best_descriptive_value;
            }
        }
        return $best_descriptive_values;
    }


    /**
     * This function is used to handle the API request. If a person exists (id is in the request) update all changed fields, else create the person and return his/her ID.
     *
     *
     * @param $person
     * @return Application|ResponseFactory|\Illuminate\Http\Response|null
     */
    public static function handleApiRequest($person) {
        // Check if the person is already stored. If so, the application should send an id.
        $storedPerson = null;
        if (array_key_exists('id',
                $person) && isset($person["id"]) and !empty($person["id"])) {
            $storedPerson = self::findOr($person["id"],
                function () {
                    return response("The given ID doesn't exist in our records",
                        404);
                });
            unset($person["id"]);
        }
        // if a person is found, we should update it
        if ($storedPerson instanceof Refugee) {
            //delete useless information (in the update case)
            $keys = [
                "date",
                "application_id",
            ];
            foreach ($keys as $key) {
                unset($person[$key]);
            }

            // update the fields if they were changed
            $storedFields = $storedPerson->fields;
            $i = 0;
            foreach ($storedFields as $field) {
                //the stored value and request value are different, we update the DB
                if (array_key_exists($field->id,
                    $person)) {
                    $storedFields->forget($i);
                    if ($field->pivot->value != $person[$field->id]) {
                        //update changed fields
                        $storedPerson->fields()->updateExistingPivot($field->id,
                            ["value" => $person[$field->id]]);
                    }
                }
                //delete when it's done
                $i++;
                unset($person[$field->id]);
            }
            //Delete the remains values
            foreach ($storedFields as $field) {
                //the stored value and request value are different, we update the DB
                $storedPerson->fields()->detach($field->id);
            }

        } else { //should create the person
            $keys = [
                "date",
                "application_id",
            ];
            $personContent = [];
            foreach ($keys as $key) {
                $personContent[$key] = $person[$key];
                unset($person[$key]);
            }
            $personContent["crew_id"] = Auth::user()->crew->id;
            $storedPerson = Refugee::create($personContent);
        }

        $fields_to_add = [];
        foreach ($person as $fieldKey => $value) {
            //check if the $fieldKey exisits in fields
            $field = Field::findOr($fieldKey,
                function () {
                    return response("The field doesn't exist.",
                        404);
                });
            if ($field instanceof Response) { //return error if the field id doesn't exist.
                return $field;
            }
            $fields_to_add[$fieldKey] = ["value" => $value];
        }
        $storedPerson->fields()->attach($fields_to_add);

        return $storedPerson;
    }

    /*
    public function relations(){
        return $this->fromRelation() + $this->toRelation();
    }
    */

    /**
     * The fields that describe the user.
     */
    public function fields() {

        return $this->belongsToMany(Field::class)->where("crew_id",
            $this->crew->id)->withPivot("id")->withPivot("value")->withTimestamps()->using(FieldRefugee::class)->orderBy("required")->orderBy("order");
    }

    public function allCoordinates() {
        return $this->fields()->whereRelation("dataType", "name", "coordinates")->get();
    }

    /**
     * This function is used to format refugees' data.
     * It takes a collection of refugees and returns an array of formatted refugees data.
     * Here is the format of the returned array:
     * [
     *     "refugee_id" => [
     *          "unique_id" => "refugee_unique_id",
     *         "gender" => "Male",
     *          "age" => 25,
     *          ...
     *    ],
     *      ...
     * ]
     * @param $refugees
     * @return array
     */
    public static function formatRefugeesData($refugees) {

        $refugeesData = [];
        foreach ($refugees as $refugee) {
            $refugeeData = [];
            foreach ($refugee->fields as $field) {
                $value = $field->pivot->value ?? "";
                if (!empty($field->linked_list)) {
                    $value = ListControl::getListElementFromId($field->linked_list,
                        $value)->displayed_value_content ?? "";
                }
                $refugeeData[$field->id] = $value;

            }
            $refugeesData[$refugee->id] = $refugeeData;

            if (empty($refugeesData[$refugee->id])) {
                unset($refugeesData[$refugee->id]);
            }
        }
        return $refugeesData;
    }

    protected static function boot() {
        parent::boot();

        static::deleting(function ($person) {
            $person->toRelation()->detach();
            $person->fromRelation()->detach();
        });

        static::creating(function ($model) {
            if (empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = Str::uuid()->toString();
            }
        });
    }

    public function toRelation() {
        return $this->belongsToMany(ListRelation::class,
            "links", "to",
            "relation_id")->using(Link::class)->wherePivotNull("deleted_at")->withPivot("from")->withPivot("id");
    }

    public function fromRelation() {
        return $this->belongsToMany(ListRelation::class,
            "links", "from",
            "relation_id")->using(Link::class)->wherePivotNull("deleted_at")->withPivot("to")->withPivot("id");
    }

    /**
     * The crew to which the refugee is associated.
     */
    public function crew()
    {
        return $this->belongsTo(Crew::class);
    }

    public function getRepresentativeValuesAttribute() {
        return $this->fields->where("representative_value",
            1);
    }

    public function getBestDescriptiveValueAttribute() {
        $best_descriptive_value = $this->fields->where("best_descriptive_value",
            1)->first();
        if(!empty($best_descriptive_value->linked_list)){
            return ListControl::getListElementFromId($best_descriptive_value->linked_list, $best_descriptive_value->pivot->value)->displayed_value_content ?? "";
        }
        return empty($best_descriptive_value) ? "" : $best_descriptive_value->pivot->value;
    }

    public function getRelationsAttribute() {
        return [
            $this->fromRelation,
            $this->toRelation,
        ];
    }

    public function hasEvent() {
        return $this->fields()->where('linked_list',
            ListControl::whereName('Event')->first()->id)->exists();
    }

    public function getEventAttribute() {
        return Event::find($this->fields()->where('linked_list',
            ListControl::whereName('Event')->first()->id)->first()->pivot->value);
    }
}
