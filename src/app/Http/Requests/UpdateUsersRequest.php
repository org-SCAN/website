<?php

namespace App\Http\Requests;

use App\Rules\NotLastMoreImportantRole;
use Illuminate\Foundation\Http\FormRequest;

class UpdateUsersRequest extends FormRequest
{
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
        $rules = [
            'name'    => [
                'string',
                'required',
            ],
            'role_id' => [
                'required',
                'exists:user_roles,id',
                new NotLastMoreImportantRole($this->route('user')),
            ],
            'crew_id' => [
                'required',
                'exists:crews,id',
            ]
        ];
        return $rules;
    }
}
