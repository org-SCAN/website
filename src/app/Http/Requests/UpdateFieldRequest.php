<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateFieldRequest extends FormRequest
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
            "title" => "required|string",
            "placeholder" => "string|max:80|nullable",
            "required" => "integer|required",
            "status" => "integer|required",
            "order" => "integer",
            "linked_list" => "uuid|exists:list_controls,id|nullable",
            "descriptive_value" => "integer|nullable",
            "best_descriptive_value" => "integer|nullable"

        ];
        return $rules;
    }
}
