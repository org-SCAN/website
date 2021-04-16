<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Field;
use Illuminate\Support\Facades\Gate;
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
            "label" => "required|string",
            "placeholder" => "string|max:80",
            "database_type" => "string|required",
            "required" => "integer|required",
            "status" => "integer|required",
            "order" => "integer"

        ];
        return $rules;
    }
}
