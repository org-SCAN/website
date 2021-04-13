<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Field;
use Illuminate\Support\Facades\Gate;
class StoreRefugeeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;//Gate::allows('manage_refugees_access');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $fields = Field::all(); // TODO : all where not deleted
        $rules = array();

        foreach ($fields as $field){
            $rules[$field["label"]]=array();
            if($field["required"] == 1){
                array_push($rules[$field["label"]], "required");
            }
            //array_push($rules[$field["label"]], $field["html_data_type"]);
        }
        return $rules;
    }
}
