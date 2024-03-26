<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEventRequest extends FormRequest
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
            "name" => "required|unique:events,name," . $this->route('event')->id . "|string",
            "event_type_id" => "required|exists:list_event_types,id",
            "country_id" => "nullable|exists:list_countries,id",
            "location_details" => "nullable|string",
            "start_date" => "nullable|date",
            "stop_date" => "nullable|date|after_or_equal:start_date",
            "description" => "nullable|string"
        ]  + \App\Http\Livewire\Forms\Coordinates::rules("coordinates");
    }
}
