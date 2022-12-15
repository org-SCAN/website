<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

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
            "placeholder" => "string|max:80|nullable",
            "data_type_id" => "uuid|required|exists:list_data_types,id",
            "range" => "boolean|nullable",
            "required" => "integer|required",
            "status" => "integer|required",
            "linked_list" => "nullable|uuid|exists:list_controls,id",
            "descriptive_value" => "integer|nullable",
            "best_descriptive_value" => "integer|nullable|unique:fields,best_descriptive_value,NULL,id,crew_id," . Auth::user()->crew->id,
            "validation_rules" => "string|nullable",
        ];
        return $rules;
    }
}
