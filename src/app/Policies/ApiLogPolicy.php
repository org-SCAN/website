<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

class ApiLogPolicy extends GlobalPolicy
{
    use HandlesAuthorization;

    public function __construct($route_name = "api_logs")
    {
        parent::__construct($route_name);
    }
}
