<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

class PlacePolicy extends GlobalPolicy
{
    use HandlesAuthorization;

    public function __construct($route_name = "permissions")
    {
        parent::__construct($route_name);
    }
}
