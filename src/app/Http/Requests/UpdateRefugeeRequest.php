<?php

namespace App\Http\Requests;

use App\Models\Field;
use Illuminate\Foundation\Http\FormRequest;

class UpdateRefugeeRequest extends FormRequest
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
        $hiddens = new Field();
        $hiddens = $hiddens->getHidden();
        $fields = Field::where("status", ">", 0)->get();
        $fields = $fields->makeVisible($hiddens)->toArray();
        $rules = array_column($fields, 'validation_laravel', "label");
        return $rules;
    }
}
