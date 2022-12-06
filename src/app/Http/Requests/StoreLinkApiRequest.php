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
            "*.id" => "uuid|exists:links,id",
            "*.from" => "Required|uuid|exists:refugees,id",
            "*.to" => "Required|uuid|exists:refugees,id",
            "*.relation_id" => "Required|exists:list_relations,id",
            "*.detail" => "String|nullable",
            "*.date" => "Required|date",
        ];
    }
}
