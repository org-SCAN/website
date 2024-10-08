<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Http\Livewire\Forms\Coordinates;
use App\Http\Livewire\Forms\Area;

class UpdatePlaceRequest extends FormRequest
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
                "name" => "required|unique:places,name," . $this->route('place')->id . "|string",
                'description' => 'nullable|string',
        ] + Coordinates::rules('coordinates') + Area::rules('area');
    }
}
