<?php

namespace App\Http\Requests;

use App\Models\Field;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class StoreRefugeeRequest extends FormRequest
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
        $fields = Field::where("status", ">", 0)->where('crew_id', Auth::user()->crew_id)->get();
        $fields = $fields->makeVisible($hiddens)->toArray();
        $rules = array_column($fields, 'validation_laravel', "id");
        // if a field is rangeable, add the range validation by splitting the validation_laravel
        foreach ($fields as $field) {
            if ($field['range']) {
                $rules[$field['id'].'.min'] = Str::replace('required','', $field['validation_laravel'])."|nullable";
                $rules[$field['id'].'.current'] = $field['validation_laravel'];
                $rules[$field['id'].'.max'] = Str::replace('required','', $field['validation_laravel'])."|nullable";
                unset($rules[$field['id']]);
            }
        }
        return $rules;

    }
}
