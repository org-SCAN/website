<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoleRequest extends Model
{
    use HasFactory, Uuids;

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
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user',
        'role',
        'granted'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['deleted', "created_at", "updated_at"]; //TODO : SI on a des bugs à cause des user roles c'est ici


    public function getRoleAttribute($value)
    {
        return UserRole::find($value)->role;
    }

    public function getRoleId()
    {
        return $this->attributes["role"];
    }

    public function getUserAttribute($value)
    {
        return User::find($value)->name;
    }

    public function getUserId()
    {
        return $this->attributes["user"];
    }
}
