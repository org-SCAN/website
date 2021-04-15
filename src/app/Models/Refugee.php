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

    protected $gards = [""];
    protected $fillable = ["*"];

    /**
     * Indicate the nationality according the UUID stored in DB
     * @param $value Is the id of the element
     * @return String
     */
    public function getNationalityAttribute($value){
        return Country::find($value)->short;
    }
    /**
     * Indicate the role according the UUID stored in DB
     * @param $value Is the id of the element
     * @return String
     */
    public function getRoleAttribute($value){
        return Role::find($value)->short;
    }
    /**
     * Indicate the gender according the UUID stored in DB
     * @param $value Is the id of the element
     * @return String
     */
    public function getGenderAttribute($value){
        return Gender::find($value)->full;
    }
    /**
     * Indicate the route according the UUID stored in DB
     * @param $value Is the id of the element
     * @return String
     */
    public function getRouteAttribute($value){
        return Route::find($value)->full;
    }
    /**
     * Indicate the residence according the UUID stored in DB
     * @param $value Is the id of the element
     * @return String
     */
    public function getResidenceAttribute($value){
        return Country::find($value)->short;
    }
}
