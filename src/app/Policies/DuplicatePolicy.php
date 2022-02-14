<?php

namespace App\Policies;

use App\Models\Duplicate;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class DuplicatePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param \App\Models\User $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        return $user->role_name == ("admin")
            ? Response::allow()
            : Response::deny('You do not have the right to do this.');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param \App\Models\User $user
     * @param \App\Models\Duplicate $duplicate
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Duplicate $duplicate)
    {
        return $user->role_name == ("admin")
            ? Response::allow()
            : Response::deny('You do not have the right to do this.');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param \App\Models\User $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return $user->role_name == ("admin")
            ? Response::allow()
            : Response::deny('You do not have the right to do this.');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param \App\Models\User $user
     * @param \App\Models\Duplicate $duplicate
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Duplicate $duplicate)
    {
        return $user->role_name == ("admin")
            ? Response::allow()
            : Response::deny('You do not have the right to do this.');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param \App\Models\User $user
     * @param \App\Models\Duplicate $duplicate
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Duplicate $duplicate)
    {
        return $user->role_name == ("admin")
            ? Response::allow()
            : Response::deny('You do not have the right to do this.');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param \App\Models\User $user
     * @param \App\Models\Duplicate $duplicate
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Duplicate $duplicate)
    {
        return $user->role_name == ("admin")
            ? Response::allow()
            : Response::deny('You do not have the right to do this.');
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param \App\Models\User $user
     * @param \App\Models\Duplicate $duplicate
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Duplicate $duplicate)
    {
        return $user->role_name == ("admin")
            ? Response::allow()
            : Response::deny('You do not have the right to do this.');
    }
}
