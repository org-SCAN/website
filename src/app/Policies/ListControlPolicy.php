<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

class ListControlPolicy extends GlobalPolicy
{
    use HandlesAuthorization;

    public function __construct($route_name = "lists_control")
    {
        parent::__construct($route_name);
    }
}
