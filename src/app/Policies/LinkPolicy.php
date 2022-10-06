<?php

namespace App\Policies;

use App\Models\Link;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class LinkPolicy
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
        return $user->role->name == ("viewer") || $user->role->name == ("editor") || $user->role->name == ("admin")
            ? Response::allow()
            : Response::deny('You do not have the right to do this.');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param \App\Models\User $user
     * @param \App\Models\Link $link
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Link $link)
    {
        return $user->role->name == ("viewer") || $user->role->name == ("editor") || $user->role->name == ("admin")
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
        return $user->role->name == ("editor") || $user->role->name == ("admin")
            ? Response::allow()
            : Response::deny('You do not have the right to do this.');
    }

    /**
     * Determine whether the user can create models using a Json
     *
     * @param \App\Models\User $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function createFromJson(User $user)
    {
        return $user->role->name == ("editor") || $user->role->name == ("admin")
            ? Response::allow()
            : Response::deny('You do not have the right to do this.');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param \App\Models\User $user
     * @param \App\Models\Link $link
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Link $link)
    {
        return $user->role->name == ("editor") || $user->role->name == ("admin")
            ? Response::allow()
            : Response::deny('You do not have the right to do this.');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param \App\Models\User $user
     * @param \App\Models\Link $link
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Link $link)
    {
        return ($user->role->name == ("editor") && $link->user->id == $user->id) || $user->role->name == ("admin")
            ? Response::allow()
            : Response::deny('You do not have the right to do this.');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param \App\Models\User $user
     * @param \App\Models\Link $link
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Link $link)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param \App\Models\User $user
     * @param \App\Models\Link $link
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Link $link)
    {
        //
    }
}
