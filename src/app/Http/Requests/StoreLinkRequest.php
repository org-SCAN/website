<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreLinkRequest extends FormRequest
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
            "refugee1" => "Required|uuid|exists:refugees,id",
            "refugee2" => "Required|uuid|exists:refugees,id|different:refugee1",
            "relation" => "Required|uuid|exists:relations,id",
        ];
    }
}
