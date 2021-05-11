<?php

namespace App\Http\Requests;
class FileLinkRequest extends StoreLinkApiRequest
{
    public function validationData()
    {
        return json_decode($this->link_json->getContent(), true);
    }

    public function rules()
    {
        return [
            "*.from_unique_id" => "Required|string|exists:refugees,unique_id",
            "*.to_unique_id" => "Required|string|exists:refugees,unique_id|different:*.from_unique_id",
            "*.relation" => "Required|exists:relations,short",
            "*.detail" => "String|nullable",
            "*.date" => "Required|date",
            "*.application_id" => "Required|string",
        ];
    }
}
