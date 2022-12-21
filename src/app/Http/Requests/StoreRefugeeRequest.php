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
    public static function rules()
    {
        $fields = Field::where("status", ">", 0)->where('crew_id', Auth::user()->crew_id)->get();
        $rules = $fields->pluck('validation_laravel', 'id')->toArray();
        // if a field is rangeable, add the range validation by splitting the validation_laravel
        foreach ($fields as $field) {
            if ($field->range) {
                $rules[$field->id.'.min'] = Str::replace('required','', $field->validation_laravel)."|nullable";
                $rules[$field->id.'.current'] = $field->validation_laravel;
                $rules[$field->id.'.max'] = Str::replace('required','', $field->validation_laravel)."|nullable";
                unset($rules[$field->id]);
            }
            if($field->dataType->model){
                foreach ($field->dataType->model::rules() as $key => $rule) {
                    $rules[$field->id.'.'.$key] = $field->validation_laravel.'|'.$rule;
                }
                unset($rules[$field->id]);
            }
        }

        // if a rule has the regex validation rule, catch the regex (and all that follow) and add it to the rule but at the end
        foreach ($rules as $key => $rule) {
            if (Str::contains($rule, 'regex')) {
                // get the text that match the given regex
                preg_match('/regex:\/(.*)\//', $rule, $matches);
                // place the text that matches the given regex : regex:\/(.*)\/ at the end of the rule
                $rules[$key] = preg_replace('/regex:\/(.*)\//', '', $rule);
                $rules[$key] .= '|regex:/'.$matches[1].'/';
            }
        }
        return $rules;

    }
}
