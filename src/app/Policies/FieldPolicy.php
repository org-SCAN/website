<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;


class FieldPolicy extends GlobalPolicy
{
    use HandlesAuthorization;

    public function __construct($route_name = "fields")
    {
        parent::__construct($route_name);
    }
}
