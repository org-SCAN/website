<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class DuplicatePolicy extends GlobalPolicy
{
    use HandlesAuthorization;

    public function __construct($route_name = "duplicate")
    {
        parent::__construct($route_name);
    }

    /**
     * Determine whether the user can force the duplicate compute command.
     *
     * @param  User  $user
     * @return mixed
     */

    public function compute(User $user)
    {
        return $this->hasPermission($user, __FUNCTION__)
            ? Response::allow()
            : Response::deny('You do not have the right to do this.');
    }
    /**
     * Determine whether the user can mark a duplicate as resolved.
     *
     * @param  User  $user
     * @return mixed
     */

    public function resolve(User $user)
    {
        return $this->hasPermission($user, __FUNCTION__)
            ? Response::allow()
            : Response::deny('You do not have the right to do this.');
    }
}
