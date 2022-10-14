<?php

namespace App\Http\Requests;

use App\Models\ListControl;
use Illuminate\Foundation\Http\FormRequest;

class UpdateListElemRequest extends FormRequest
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
        $list_fields = $list->structure;
        // I wanna check if the displayed field is set so as the key value, if the key isn't the id
        $rules = [];
        foreach ($list_fields as $field) {
            $rules[$field->field] = 'Required';
        }
        return $rules;
    }
}
