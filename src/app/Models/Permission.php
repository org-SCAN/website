<?php

namespace App\Models;

use App\Traits\ModelEventsLogs;
use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Permission extends Model
{
    use HasFactory, Uuids, SoftDeletes, ModelEventsLogs;

    public static $alwaysAuthorizedRoute = ["livewire"];

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

    public static function getPolicyRouteNameFromRouteName($route_name)
    {
        $route = explode(".", $route_name);
        $policyRouteName = [
            "index" => "viewAny",
            "show" => "view",
            "create" => "create",
            "store" => "create",
            "edit" => "update",
            "update" => "update",
            "destroy" => "delete",
            "store_from_json" => "createFromJson",
            "reject_role" => "grantRole",
            "edit_list_elem" => "updateListElem",
            "store_displayed_value" => "create",
            "create_fields" => "create",
            "store_fields" => "create",
            "update_list" => "addToList"
        ];
        $method = array_key_exists($route[1], $policyRouteName)
            ? $policyRouteName[$route[1]]
            : Str::camel($route[1]);

        return $route[0] . "." . $method;
    }

    public static function getRoutesWithPermission()
    {
        $routes_with_permissions = [
            "user", "person",
            "links",
            "cytoscape",
            "fields",
            "lists_control",
            "duplicate",
            "crew", "roles",
            "event", "source",
            "place",
        ];
        if (env('APP_DEBUG') or env('APP_ENV') == "testing"){
            array_push($routes_with_permissions, "permissions");
        }
        return $routes_with_permissions;
    }
}
