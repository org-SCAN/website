<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class RefugeePolicy extends GlobalPolicy
{
    use HandlesAuthorization;

    public function __construct($route_name = "person")
    {
        parent::__construct($route_name);
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  User  $user
     * @return Response|bool
     */
    public function create(User $user) {
        return $this->hasPermission($user,
            __FUNCTION__) && $user->crew->fields()->where('status','>', 0)->get()->count() > 0 ? Response::allow() : Response::deny(self::ERROR_MESSAGE);
    }


    /**
     * Determine whether the user can create from json models.
     *
     * @param  User  $user
     * @return Response|bool
     */
    public function createFromJson(User $user) {
        return $this->hasPermission($user,
            __FUNCTION__) && $user->crew->fields()->where('status','>', 0)->get()->count() > 0 ? Response::allow() : Response::deny(self::ERROR_MESSAGE);
    }
}
