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
     * Indicate the nationality according the UUID stored in DB
     * @param $value Is the id of the element
     * @return String
     */
    public function getNationalityAttribute($value){
        $country = Country::find($value);
        return empty($country) ? "" : $country->short;
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
     * Indicate the gender according the UUID stored in DB
     * @param $value Is the id of the element
     * @return String
     */
    public function getGenderAttribute($value){
        $gender = Gender::find($value);
        return empty($gender) ? "" : $gender->full;
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
     * Indicate the residence according the UUID stored in DB
     * @param $value Is the id of the element
     * @return String
     */
    public function getResidenceAttribute($value){
        $country = Country::find($value);
        return empty($country) ? "" : $country->short;
    }
}
