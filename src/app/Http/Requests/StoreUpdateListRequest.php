<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUpdateListRequest extends FormRequest
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
        $list = $this->route('listControl');
        $list_fields = $list->getListFields();
        if ($list->key_value == "id") {
            unset($list_fields[array_keys($list_fields, "key")[0]]);
        }
        // I wanna check if the displayed field is set so as the key value, if the key isn't the id
        $rules = [];
        foreach ($list_fields as $field) {
            $rules[$field] = 'Required';
        }
        return $rules;
    }
}
