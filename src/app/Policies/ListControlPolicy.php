<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;
use Illuminate\Database\Eloquent\Model;

class ListControlPolicy extends GlobalPolicy
{
    use HandlesAuthorization;

    public function __construct($route_name = "lists_control")
    {
        parent::__construct($route_name);
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param \App\Models\User $user
     * @param Model $model
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function addToList(User $user, Model $model)
    {
        return $this->hasPermission($user, __FUNCTION__)
            ? Response::allow()
            : Response::deny('You do not have the right to do this.');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param \App\Models\User $user
     * @param Model $model
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function updateListElem(User $user)
    {
        return $this->hasPermission($user, __FUNCTION__)
            ? Response::allow()
            : Response::deny('You do not have the right to do this.');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param \App\Models\User $user
     * @param Model $model
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function deleteListElem(User $user)
    {
        return $this->hasPermission($user, __FUNCTION__)
            ? Response::allow()
            : Response::deny('You do not have the right to do this.');
    }

}
