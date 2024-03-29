<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

class EventPolicy extends GlobalPolicy
{
    use HandlesAuthorization;

    public function __construct($route_name = "event")
    {
        parent::__construct($route_name);
    }
}
