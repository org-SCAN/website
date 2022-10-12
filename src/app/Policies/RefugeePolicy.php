<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;


class RefugeePolicy extends GlobalPolicy
{
    use HandlesAuthorization;

    public function __construct($route_name = "person")
    {
        parent::__construct($route_name);
    }
}
