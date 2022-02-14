<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreFieldRequest extends FormRequest
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
            "label" => "required|string|unique:fields,label,null,deleted_at",
            "placeholder" => "string|max:80|nullable",
            "database_type" => "string|required",
            "required" => "integer|required",
            "status" => "integer|required",
            "linked_list" => "nullable|uuid|exists:list_controls,id",
            "order" => "integer|nullable"

        ];
        return $rules;
    }
}
