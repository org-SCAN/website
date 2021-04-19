<?php


namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Field;
use Illuminate\Support\Facades\Gate;

class StoreRefugeeApiRequest extends StoreRefugeeRequest
{
    public function validationData()
    {
        return $this->json()->all();
    }

    public function rules()
    {
        $rules = parent::rules();
        $rules["date"] = "date";
        $rules = array_combine(
            array_map(function($key){ return '*.'.$key; }, array_keys($rules)),
            $rules
        );
        return $rules;
    }
}
