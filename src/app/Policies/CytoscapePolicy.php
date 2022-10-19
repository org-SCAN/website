<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

class CytoscapePolicy extends GlobalPolicy
{
    use HandlesAuthorization;

    public function __construct($route_name = "cytoscape")
    {
        parent::__construct($route_name);
    }
}
