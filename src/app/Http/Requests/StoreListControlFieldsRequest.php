<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreListControlFieldsRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            "fields" => 'required|array|min:1',
            "fields.*.name" => 'required|string',
            "fields.*.data_type_id" => 'required|uuid|exists:list_data_types,id',
            "fields.*.required" => 'boolean',
        ];
    }
}
