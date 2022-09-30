<?php

namespace App\Rules;

use App\Models\User;
use App\Models\UserRole;
use Illuminate\Contracts\Validation\InvokableRule;
use Illuminate\Support\Facades\Auth;

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
    public function __invoke($attribute, $value, $fail)
    {
        $actual_role = Auth::user()->role;

        $sup_role = UserRole::where("importance", ">=", $actual_role->importance)->get()->pluck("id")->all();
        $count = User::whereIn("role_id", $sup_role)->get();

        if ($count->count() <= 1) {
            $fail('You are the user with the highest permissions, you can\'t change your role : you will not be able to get it back');
        }
    }
}
