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
        $rules = parent::rules();
        $rules["*.application_id"] = "Required|string";
        return $rules;
    }
}
