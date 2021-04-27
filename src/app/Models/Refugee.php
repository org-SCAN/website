<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

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
     * Get the good element ID according it's key
     * @param $model
     * @param $attribute
     * @param $value
     */
    protected function setLinkedListElement($model, $attribute, $value){
        if(Str::isUuid($value) && $model::find($value)->exists()){
            $this->attributes[$attribute] = $value;
        }
        else{
            $val = $model::where(ListControl::where("name", $model)->first()->key_value,$value)->first();
            if(preg_match('/[A-Z]{3}/', $value) &&  $val->exists()){
                $this->attributes[$attribute] = $val->id;
            }
        }
    }

    /**
     * Indicate the nationality according the UUID stored in DB
     * @param $value Is the id of the element
     * @return String
     */
    public function getNationalityAttribute($value){
        $country = Country::find($value);
        return empty($country) ? "" : $country->short;
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
        $this->setLinkedListElement("Country", "nationality", $value);
    }

    /**
     * Store the sex id accorting its key or its code
     * @param $value
     */

    public function setGenderAttribute($value){
        $this->setLinkedListElement("Gender", "gender", $value);
    }

    /**
     * Store the role id accorting its key or its code
     * @param $value
     */

    public function setRoleAttribute($value){
        $this->setLinkedListElement("Role", "role", $value);
    }

    /**
     * Store the route id accorting its key or its code
     * @param $value
     */

    public function setRouteAttribute($value){
        $this->setLinkedListElement("Route", "route", $value);
    }

    /**
     * Indicate the role according the UUID stored in DB
     * @param $value Is the id of the element
     * @return String
     */
    public function getRoleAttribute($value){
        $role = Role::find($value);
        return empty($role) ? "" : $role->short;
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
        $gender = Gender::find($value);
        return empty($gender) ? "" : $gender->full;
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
        $route = Route::find($value);
        return empty($route) ? "" : $route->short;
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
        $country = Country::find($value);
        return empty($country) ? "" : $country->short;
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
