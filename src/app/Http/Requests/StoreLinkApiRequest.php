<?php

namespace App\Http\Requests;

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
            "*.from_full_name" => "Required|string|exists:refugees,full_name",
            "*.from_unique_id" => "Required|string|exists:refugees,unique_id",
            "*.to_full_name" => "Required|string|exists:refugees,full_name",
            "*.to_unique_id" => "Required|string|exists:refugees,unique_id",
            "*.relation" => "Required|uuid|exists:relations,id",
            "*.detail" => "String|nullable",
        ];
    }
}
