<?php

namespace App\Policies;

use App\Models\Permission;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;
use Illuminate\Database\Eloquent\Model;

class RolePolicy extends GlobalPolicy
{
    use HandlesAuthorization;

    public function __construct($route_name = "roles")
    {
        parent::__construct($route_name);
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param \App\Models\User $user
     * @param Model $model
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Model $role)
    {
        return $this->hasPermission($user, __FUNCTION__) && $role->permissions->count() < Permission::count()
            ? Response::allow()
            : Response::deny('You do not have the right to do this.');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param \App\Models\User $user
     * @param Model $model
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Model $role)
    {
        return $this->hasPermission($user, __FUNCTION__) && $role->permissions->count() < Permission::count()
            ? Response::allow()
            : Response::deny('You do not have the right to do this.');
    }
}
