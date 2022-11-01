<?php

namespace App\Http\Requests;

use App\Rules\PersonHasEvent;
use Illuminate\Foundation\Http\FormRequest;

class StoreLinkRequest extends FormRequest
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
        return [
            "from" => [
                "required_without:everyoneFrom",
                new PersonHasEvent($this)
            ],
            "everyoneFrom" => "boolean|prohibits:everyoneTo|required_without:from|nullable",

            "to" => [
                "required_without:everyoneTo",
                new PersonHasEvent($this)
            ],
            "everyoneTo" => "boolean|prohibits:everyoneFrom|required_without:to|nullable",

            "relation" => "Required|uuid|exists:list_relations,id",
            "detail" => "string|nullable",
        ];
    }
}
