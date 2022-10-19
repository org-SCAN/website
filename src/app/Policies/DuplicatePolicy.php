<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

class DuplicatePolicy extends GlobalPolicy
{
    use HandlesAuthorization;

    public function __construct($route_name = "duplicate")
    {
        parent::__construct($route_name);
    }
}
