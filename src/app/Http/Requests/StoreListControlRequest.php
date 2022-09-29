<?php


namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreListControlRequest extends FormRequest
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
        $rules =[
            "title" => "string|required",
            "displayed_value" => "string|required",
            "name" => "string|required|unique:list_controls,name",
            "key_value" => "string|required"
        ];
        return $rules;
    }
}
