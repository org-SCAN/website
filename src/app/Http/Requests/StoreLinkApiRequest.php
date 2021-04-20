<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreLinkApiRequest extends StoreLinkRequest
{

    public function validationData()
    {
        return $this->json()->all();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "*.refugee1_full_name" => "Required|string|exists:refugees,full_name",
            "*.refugee1_unique_id" => "Required|string|exists:refugees,unique_id",
            "*.refugee2_full_name" => "Required|string|exists:refugees,full_name",
            "*.refugee2_unique_id" => "Required|string|exists:refugees,unique_id",
            "*.relation" => "Required|uuid|exists:relations,id",
        ];
    }
}
