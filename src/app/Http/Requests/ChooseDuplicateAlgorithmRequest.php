<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ChooseDuplicateAlgorithmRequest extends FormRequest
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
            'matching_algorithm_id' => 'required|exists:list_matching_algorithms,id',
        ];
    }
}
