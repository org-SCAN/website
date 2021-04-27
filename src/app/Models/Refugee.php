<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Refugee extends Model
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
     * @var mixed
     */
    private $residence;


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
    public function getRouteId(){
        return $this->attributes['route'];
    }
    /**
     * Indicate the residence according the UUID stored in DB
     * @param $value Is the id of the element
     * @return String
     */
    public function getResidenceAttribute($value){

        return Country::getDisplayedValueContent($value);
    }

    /**
     * Indicate the the UUID stored in DB
     * @param $value Is the id of the element
     * @return String
     */
    public function getResidenceId(){
        return $this->attributes['residence'];
    }
}
