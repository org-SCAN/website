<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Refugee extends Model
{
    use HasFactory, Uuids, SoftDeletes;
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
     * @var mixed
     */

    /**
     * Give the route pattern, used in api log
     * @var string
     */
    const route_base = "manage_refugees";

    /**
     * The fields that describe the user.
     */
    public function fields()
    {
        return $this->belongsToMany(Field::class)->withTimestamps();;
    }


    /**
     * It returns a representative value, witch could be shown to discribe the element
     *
     * @return mixed
     */
    public function getRepresentativeValue()
    {
        return $this->full_name;
    }

    /**
     * Indicate the nationality according the UUID stored in DB
     * @param $value Is the id of the element
     * @return String
     */
    public function getNationalityAttribute($value){
        return Country::getDisplayedValueContent($value);
    }
    /**
     * Indicate the the UUID stored in DB
     * @param $value Is the id of the element
     * @return String
     */
    public function getNationalityId(){
        return $this->attributes['nationality'];
    }

    /**
     * Store the country id accorting its key or its ISO3 contry code
     * @param $value
     */

    public function setNationalityAttribute($value){
        $this->attributes["nationality"] = Country::getIdFromValue($value);
    }

    /**
     * Store the sex id accorting its key or its code
     * @param $value
     */

    public function setGenderAttribute($value){
        $this->attributes["gender"] = Gender::getIdFromValue($value);
    }

    /**
     * Store the role id accorting its key or its code
     * @param $value
     */

    public function setRoleAttribute($value){
        $this->attributes["role"] = Role::getIdFromValue($value);
    }

    /**
     * Store the route id accorting its key or its code
     * @param $value
     */

    public function setRouteAttribute($value){
        $this->attributes["route"] = Route::getIdFromValue($value);
    }

    /**
     * Store the residence id accorting its key or its code
     * @param $value
     */

    public function setResidenceAttribute($value){
        $this->attributes["residence"] = Country::getIdFromValue($value);
    }

    /**
     * Indicate the role according the UUID stored in DB
     * @param $value Is the id of the element
     * @return String
     */
    public function getRoleAttribute($value){
        return Role::getDisplayedValueContent($value);
    }
    /**
     * Indicate the the UUID stored in DB
     * @param $value Is the id of the element
     * @return String
     */
    public function getRoleId(){
        return $this->attributes['role'];
    }
    /**
     * Indicate the gender according the UUID stored in DB
     * @param $value Is the id of the element
     * @return String
     */
    public function getGenderAttribute($value){
        return Gender::getDisplayedValueContent($value);
    }

    /**
     * Indicate the the UUID stored in DB
     * @param $value Is the id of the element
     * @return String
     */
    public function getGenderId(){
        return $this->attributes['gender'];
    }
    /**
     * Indicate the route according the UUID stored in DB
     * @param $value Is the id of the element
     * @return String
     */
    public function getRouteAttribute($value){
        return Route::getDisplayedValueContent($value);
    }

    /**
     * Indicate the the UUID stored in DB
     * @param $value Is the id of the element
     * @return String
     */
    public function getRouteId()
    {
        return $this->attributes['route'];
    }

    /**
     * Indicate the residence according the UUID stored in DB
     * @param $value Is the id of the element
     * @return String
     */
    public function getResidenceAttribute($value)
    {

        return Country::getDisplayedValueContent($value);
    }

    /**
     * Indicate the the UUID stored in DB
     * @param $value Is the id of the element
     * @return String
     */
    public function getResidenceId()
    {
        return $this->attributes['residence'];
    }

    public static function getRefugeeIdFromReference($reference, $application_id)
    {
        $refugee = self::where("application_id", $application_id)->where('unique_id', $reference)->first();

        return !empty($refugee) ? $refugee->id : null;
    }

    public static function handleApiRequest($refugee)
    {
        $potential_refugee = Refugee::getRefugeeIdFromReference($refugee["unique_id"], $refugee["application_id"]);
        $ref = null;
        if ($potential_refugee != null) {
            $potential_refugee = self::find($potential_refugee);

            if (isset($refugee["date_update"])) {
                if ($refugee["date_update"] > $potential_refugee->updated_at) {
                    if ($potential_refugee->application_id != $refugee["application_id"]) {
                        foreach ($refugee as $field => $refugee_field) {
                            if ($potential_refugee->$refugee_field != null) {
                                unset($refugee[$refugee_field]);
                            }
                        }
                    }
                } else {
                    foreach ($refugee as $field => $refugee_field) {
                        if ($potential_refugee->$refugee_field != null) {
                            unset($refugee[$refugee_field]);
                        }
                    }
                }
                unset($refugee["date_update"]);
                $potential_refugee->update($refugee);
                $ref = true;
            } else {
                $ref = true;
            }
        } else {
            unset($refugee["date_update"]);
            Refugee::create($refugee);
            $ref = true;
        }
        return $ref;
    }
}
