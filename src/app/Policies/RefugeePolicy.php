<?php

namespace App\Policies;

use App\Models\Refugee;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;


class RefugeePolicy
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
        return $user->role_name == ("viewer") || $user->role_name == ("editor") || $user->role_name == ("admin")
            ? Response::allow()
            : Response::deny('You do not have the right to do this.');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param \App\Models\User $user
     * @param \App\Models\Refugee $person
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Refugee $person)
    {

        return $user->role_name == ("viewer") || $user->role_name == ("editor") || $user->role_name == ("admin")
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
        return $user->role_name == ("editor") || $user->role_name == ("admin")
            ? Response::allow()
            : Response::deny('You do not have the right to do this.');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param \App\Models\User $user
     * @param \App\Models\Refugee $person
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Refugee $person)
    {
        return $user->role_name == ("editor") || $user->role_name == ("admin")
            ? Response::allow()
            : Response::deny('You do not have the right to do this.');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param \App\Models\User $user
     * @param \App\Models\Refugee $person
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Refugee $person)
    {
        return ($user->role_name == ("editor") && $person->user->id == $user->id) || $user->role_name == ("admin")
            ? Response::allow()
            : Response::deny('You do not have the right to do this.');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param \App\Models\User $user
     * @param \App\Models\Refugee $person
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Refugee $person)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param \App\Models\User $user
     * @param \App\Models\Refugee $person
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Refugee $person)
    {
        //
    }
}
