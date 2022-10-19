<?php

namespace App\Rules;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Contracts\Validation\InvokableRule;

class NotLastMoreImportantRole implements InvokableRule
{
    /**
     * Run the validation rule.
     *
     * @param string $attribute
     * @param mixed $value
     * @param \Closure $fail
     * @return void
     */

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public User $user;

    public function __invoke($attribute, $value, $fail)
    {


        $sup_role = Role::has('permissions', Permission::count())->first();
        $count = User::whereIn("role_id", $sup_role)->get();

        if ($this->user->role->id == $sup_role->id && $count->count() <= 1 && $this->user->role->id != $value) {
            $fail('You are the user with the highest permissions, you can\'t change your role : you will not be able to get it back');
        }
    }
}
