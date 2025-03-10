<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Livewire\Forms\Coordinates;
use App\Livewire\Forms\Area;

class StorePlaceRequest extends FormRequest
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

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => 'required|string|max:255|unique:places,name',
            'description' => 'nullable|string',
        ] + Coordinates::rules('coordinates') + Area::rules('area');
    }
}
