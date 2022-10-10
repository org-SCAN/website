<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Permission extends Model
{
    use HasFactory, Uuids, SoftDeletes;

    public $incrementing = false;

    /**
     * Indicates if the model's ID is auto-incrementing.
     *
     * @var bool
     */
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
    protected $hidden = ['deleted_at', "created_at", "updated_at"];

    /**
     * The roles that belong to the user.
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class)->using(PermissionRole::class)->withTimestamps()->withPivot("id");
    }

    public function getPolicyRouteNameFromRouteName($route_name)
    {
        $route = explode(".", $route_name);
        $policyRouteName = [
            "create" => "store",
            "store" => "store",
            "index" => "view_any",
        ];

        return $route[0] . "." . $policyRouteName[$route[1]];
    }
}
