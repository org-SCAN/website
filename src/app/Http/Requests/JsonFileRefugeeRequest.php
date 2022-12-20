<?php

namespace App\Http\Requests;

class JsonFileRefugeeRequest extends StoreRefugeeApiRequest
{
    public function rules()
    {
        return parent::rules() + ["*.application_id" => "required"];
    }

    public function validationData()
    {

        return json_decode($this->import_person_file->getContent(), true);
    }


}
