<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Model;

class ApiLog extends Model
{
    use Uuids;
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
     * Get the user email
     *
     * @param $value
     * @return string (user's email)
     */
    public function getUserAttribute($value){
        return User::find($value)->email;
    }

    /**
     * Return the guid of the user
     *
     * @return guid
     */
    public function getUserId(){
        return $this->attributes['user'];
    }
}
