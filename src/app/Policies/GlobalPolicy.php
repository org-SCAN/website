<?php

namespace App\Policies;

use App\Models\Permission;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;
use Illuminate\Database\Eloquent\Model;

class GlobalPolicy
{
    use HandlesAuthorization;

    const ERROR_MESSAGE = 'You do not have the right to do this.';
    protected string $route_name;

    public function __construct($route_name) {
        $this->route_name = $route_name;
    }

    /**
     * Determine whether the user can view any models.
     *
     * @param  User  $user
     * @return Response|bool
     */
    public function viewAny(User $user) {
        return $this->hasPermission($user,
            __FUNCTION__) ? Response::allow() : Response::deny(self::ERROR_MESSAGE);
    }

    /**
     * This function is user to check if a user has access to a route
     *
     * @param $user
     * @param $function
     * @param $route_name
     * @return boolean
     */
    public function hasPermission(
        $user,
        $function
    ) {
        $route_name = $this->route_name;
        $route_base = explode(".",
            $route_name)[0];

        if (in_array($route_base,
            Permission::$alwaysAuthorizedRoute)) {
            return true;
        }
        return $user->role->permissions()->where('permissions.id',
            Permission::firstWhere("policy_route",
                $route_base.".".$function)->id ?? "")->exists();
    }

    /**
     * Determine whether the user can view any models.
     *
     * @param  User  $user
     * @return Response
     */
    public function viewMenu(User $user) {
        $child_class = explode("\\",
            get_class($this));
        $child_class = end($child_class);
        return $this->hasPermission($user,
            "viewAny") ? Response::allow() : Response::deny(self::ERROR_MESSAGE);
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  User  $user
     * @return Response
     */
    public function view(
        User $user,
        Model $model
    ) {
        return $this->hasPermission($user,
            __FUNCTION__) ? Response::allow() : Response::deny(self::ERROR_MESSAGE);
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  User  $user
     * @return Response
     */
    public function create(User $user) {
        return $this->hasPermission($user,
            __FUNCTION__) ? Response::allow() : Response::deny(self::ERROR_MESSAGE);
    }

    /**
     * Determine whether the user can create models using a Json
     *
     * @param  User  $user
     * @return Response
     */
    public function createFromJson(User $user) {
        return $this->hasPermission($user,
            __FUNCTION__) ? Response::allow() : Response::deny(self::ERROR_MESSAGE);
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  User  $user
     * @param  Model  $model
     * @return Response|bool
     */
    public function update(
        User $user,
        Model $model
    ) {
        return $this->hasPermission($user,
            __FUNCTION__) ? Response::allow() : Response::deny(self::ERROR_MESSAGE);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  User  $user
     * @param  Model  $model
     * @return Response
     */
    public function delete(
        User $user,
        Model $model
    ) {
        return $this->hasPermission($user,
            __FUNCTION__) ? Response::allow() : Response::deny(self::ERROR_MESSAGE);
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  User  $user
     * @param  Model  $model
     * @return Response
     */
    public function restore(
        User $user,
        Model $model
    ) {
        return $this->hasPermission($user,
            __FUNCTION__) ? Response::allow() : Response::deny(self::ERROR_MESSAGE);
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  User  $user
     * @param  Model  $model
     * @return Response
     */
    public function forceDelete(
        User $user,
        Model $model
    ) {
        return $this->hasPermission($user,
            __FUNCTION__) ? Response::allow() : Response::deny(self::ERROR_MESSAGE);
    }
}
