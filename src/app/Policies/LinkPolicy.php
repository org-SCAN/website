<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

class LinkPolicy extends GlobalPolicy
{
    use HandlesAuthorization;

    public function __construct($route_name = "links")
    {
        parent::__construct($route_name);
    }
}
