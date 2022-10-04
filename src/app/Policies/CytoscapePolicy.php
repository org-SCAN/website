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
        return $user->role->role == ("viewer") || $user->role->role == ("editor") || $user->role->role == ("admin")
            ? Response::allow()
            : Response::deny('You do not have the right to do this.');
    }
}
