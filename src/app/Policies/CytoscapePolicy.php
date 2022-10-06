<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class CytoscapePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can access the cytoscape view
     *
     * @param \App\Models\User $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        return $user->role->name == ("viewer") || $user->role->name == ("editor") || $user->role->name == ("admin")
            ? Response::allow()
            : Response::deny('You do not have the right to do this.');
    }
}
