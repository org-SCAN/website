<?php

namespace App\Http\Requests;

use App\Rules\NotLastMoreImportantRole;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreRequestRoleRequest extends FormRequest
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
        return [
            "role" => ['string', 'required', 'exists:user_roles,id', new NotLastMoreImportantRole(Auth::user())]
        ];
    }
}
