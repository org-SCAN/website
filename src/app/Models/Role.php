<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Role extends Model
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
     * The attributes that are mass assignable.
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

    public function users(){
        return $this->hasMany(User::class);
    }

    public static function biggestRole()
    {
        return self::has("permissions", Permission::count())->first()->id;
    }

    public static function smallestRole()
    {
        $nbPerm = Permission::count();
        $smallest_role = new Role;
        foreach (Role::all() as $role) {
            if ($role->permissions->count() < $nbPerm) {
                $smallest_role = $role;
            }
        }

        return $smallest_role->id;
    }

    /**
     * The permissions that belong to the user.
     */
    public function permissions()
    {
        return $this->belongsToMany(Permission::class)->using(PermissionRole::class)->withTimestamps()->withPivot("id");
    }

    public static function getRouteBases()
    {

        $permissions = Permission::all()->sortBy("policy_route")->unique("policy_route");
        $route_bases = $permissions->map(function ($elem) {
            $exploded = explode(".", $elem->policy_route);
            return Str::camel($exploded[0]);
        })->unique();

        return $route_bases;
    }

    public static function getSortedPermisisons($route_bases)
    {

        $permissions = Permission::all()->sortBy("policy_route")->unique("policy_route");
        $sorted_permissions = [];
        foreach ($route_bases as $route_base) {
            $sorted_permissions[$route_base] = [];
            foreach ($permissions as $permission) {
                if (Str::is(Str::snake($route_base) . "*", $permission->policy_route)) {
                    $sorted_permissions[$route_base][$permission->id] = $permission->policy_route;
                }
            }
        }
        return $sorted_permissions;
    }

    public static function list() {
        // order by displayed value and pluck it
        $displayed_value = "name";

        return self::orderBy($displayed_value)->pluck($displayed_value,
            'id');
    }
}
