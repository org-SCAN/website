<?php

namespace App\Policies;

use App\Models\Permission;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

class GlobalPolicy
{
    use HandlesAuthorization;

    protected string $route_name;

    public function __construct($route_name)
    {
        $this->route_name = $route_name;
    }

    /**
     * Determine whether the user can view any models.
     *
     * @param \App\Models\User $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        return $this->hasPermission($user, __FUNCTION__)
            ? Response::allow()
            : Response::deny('You do not have the right to do this.');
    }

    public function hasPermission($user, $function, $route_name = null)
    {
        $route_name = ($route_name == null)
            ? Route::getCurrentRoute()->action["as"]
            : $route_name;
        $route_base = explode(".", $route_name)[0];
        return $user->role->permissions()->where('permissions.id', Permission::firstWhere("policy_route", $route_base . "." . $function)->id)->exists();
    }

    /**
     * Determine whether the user can view any models.
     *
     * @param \App\Models\User $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewMenu(User $user)
    {
        $child_class = explode("\\", get_class($this));
        $child_class = end($child_class);
        $fake_route = Str::snake(Str::replace("Policy", "", $child_class)) . "." . "index";
        return $this->hasPermission($user, "viewAny", $this->route_name)
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
    public function view(User $user, Model $model)
    {
        return $this->hasPermission($user, __FUNCTION__)
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
        return $this->hasPermission($user, __FUNCTION__)
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
        return $this->hasPermission($user, __FUNCTION__)
            ? Response::allow()
            : Response::deny('You do not have the right to do this.');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param \App\Models\User $user
     * @param Model $model
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Model $model)
    {
        return $this->hasPermission($user, __FUNCTION__)
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
    public function delete(User $user, Model $model)
    {
        return $this->hasPermission($user, __FUNCTION__)
            ? Response::allow()
            : Response::deny('You do not have the right to do this.');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param \App\Models\User $user
     * @param Model $model
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Model $model)
    {
        return $this->hasPermission($user, __FUNCTION__)
            ? Response::allow()
            : Response::deny('You do not have the right to do this.');
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param \App\Models\User $user
     * @param Model $model
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Model $model)
    {
        return $this->hasPermission($user, __FUNCTION__)
            ? Response::allow()
            : Response::deny('You do not have the right to do this.');
    }
}
