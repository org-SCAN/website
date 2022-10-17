<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

class CrewPolicy extends GlobalPolicy
{
    use HandlesAuthorization;

    public function __construct($route_name = "crew")
    {
        parent::__construct($route_name);
    }
}
