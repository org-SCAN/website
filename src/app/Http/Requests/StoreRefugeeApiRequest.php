<?php


namespace App\Http\Requests;

class StoreRefugeeApiRequest extends StoreRefugeeRequest
{
    public function validationData()
    {
        return $this->json()->all();
    }

    public function rules()
    {
        $rules = parent::rules();
        $rules["date"] = "Required|date";
        $rules["date_update"] = "date";
        $rules = array_combine(
            array_map(function ($key) {
                return '*.' . $key;
            }, array_keys($rules)),
            $rules
        );
        return $rules;
    }
}
