<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

class SourcePolicy extends GlobalPolicy
{
    use HandlesAuthorization;

    public function __construct($route_name = "source")
    {
        parent::__construct($route_name);
    }
}

