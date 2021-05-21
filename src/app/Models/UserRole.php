<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserRole extends Model
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
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'role',
        'importance'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['deleted', "created_at", "updated_at"];


    public function hasPermission(string $routeName)
    {

        $routeNameExploded = explode(".", $routeName);

        $permissions = [
            "api_logs" => 3,
            "cytoscape" => [
                "index" => 1
            ],
            "duplicate" => 3,
            "fields" => 3,
            "lists_control" => 3,
            "links" => [
                "index" => 1,
                "store" => 2,
                "create" => 2,
                "show" => 1,
                "destroy" => 2,
                "edit" => 2,
                "json" => 2
            ],
            "manage_refugees" => [
                "index" => 1,
                "store" => 2,
                "create" => 2,
                "show" => 1,
                "destroy" => 2,
                "edit" => 2,
                "json" => 2
            ],
            "user" => 3,
        ];

        if (is_array($permissions[$routeNameExploded[0]])) {
            if (is_array($permissions[$routeNameExploded[0]][$routeNameExploded[1]])) {
                return ($this->importance >= $permissions[$routeNameExploded[0]][$routeNameExploded[1]][$routeNameExploded[2]]);
            }
            return ($this->importance >= $permissions[$routeNameExploded[0]][$routeNameExploded[1]]);
        }

        return ($this->importance >= $permissions[$routeNameExploded[0]]);
    }
}
