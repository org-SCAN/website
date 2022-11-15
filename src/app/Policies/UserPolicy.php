<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class UserPolicy extends GlobalPolicy
{
    use HandlesAuthorization;

    public function __construct($route_name = "user") {
        parent::__construct($route_name);
    }

    public function grantRole(User $user) {
        return $this->hasPermission($user,
            __FUNCTION__) ? Response::allow() : Response::deny(parent::ERROR_MESSAGE);
    }


    public function changeTeam(User $user) {
        return $this->hasPermission($user,
            __FUNCTION__,
            'user.changeTeam') ? Response::allow() : Response::deny(parent::ERROR_MESSAGE);
    }

    public function requestRole(User $user) {

        return $this->hasPermission($user,
            __FUNCTION__,
            'user.requestRole') ? Response::allow() : Response::deny(parent::ERROR_MESSAGE);
    }

    public function invite(User $user) {

        return $this->hasPermission($user,
            __FUNCTION__,
            'user.invite') ? Response::allow() : Response::deny(parent::ERROR_MESSAGE);
    }
}
