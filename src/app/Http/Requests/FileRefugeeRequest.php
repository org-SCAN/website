<?php

namespace App\Http\Requests;

class FileRefugeeRequest extends StoreRefugeeApiRequest
{
    public function validationData()
    {
        return json_decode($this->refugee_json->getContent(), true);
    }

    public function rules()
    {
        return parent::rules() + ["*.application_id" => "required"];
    }
}
