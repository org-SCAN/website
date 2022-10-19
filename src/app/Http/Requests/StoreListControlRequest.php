<?php


namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class StoreListControlRequest extends FormRequest
{

    /*public function __construct(){
        parent::__construct();
        ddd($this);

    }*/
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
     * @return array
     */
    public function rules()
    {
        $rules =[
            "title" => "string|required"//unique where id = id
        ];
        return $rules;
    }
}
