<?php

namespace App\Http\Requests;

use App\Actions\Fortify\PasswordValidationRules;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

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
            'invite' => ["required_without:password", "boolean", "nullable"],
            'password' => [
                'required_without:invite',
                'nullable',
                'string',
                Password::min(8)
                    ->mixedCase()
                    ->letters()
                    ->numbers()
            ],
            'password_confirmation' => [
                'required_without:invite',
                'nullable',
                'same:password',
            ],
            'role' => [
                'required',
                'exists:roles,id',
            ],
            'team' => [
                'required',
                "exists:crews,id"
            ]
        ];
    }
}
