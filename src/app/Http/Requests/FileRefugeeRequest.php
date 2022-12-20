<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FileRefugeeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        // validate the file, the format should be json, csv, xlsx or xls. The file is required
        return [
            "import_person_file" => 'required|file|mimes:json,csv,xlsx,xls',
        ];
    }
}
