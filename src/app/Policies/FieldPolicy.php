<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;
use Illuminate\Database\Eloquent\Model;


class FieldPolicy extends GlobalPolicy
{
    use HandlesAuthorization;

    public function __construct($route_name = "fields")
    {
        parent::__construct($route_name);
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
        return false
            ? Response::allow()
            : Response::deny('You do not have the right to do this.');
    }
}
