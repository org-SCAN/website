<?php

namespace App\Http\Requests;

use App\Actions\Fortify\PasswordValidationRules;
use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    use PasswordValidationRules;
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => \App\Actions\Fortify\PasswordValidationRules :: passwordRules(),
            'role'    => [
                'required',
                'exists:user_roles,id',
            ],
        ];
    }
}
