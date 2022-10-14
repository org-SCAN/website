<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

class RolePolicy extends GlobalPolicy
{
    use HandlesAuthorization;

    public function __construct($route_name = "roles")
    {
        parent::__construct($route_name);
    }
}
