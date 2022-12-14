<?php

namespace App\Http\Livewire;

use Livewire\Component;

class CreateFormAdditionalFieldInformations extends Component
{
    public $rangeable = false;

    // get the selected field from the dropdown
    public function isRangeable($field)
    {
        $this->rangeable = $field->dataType->rangeable;
    }

    public function render()
    {
        return view('livewire.create-form-additional-field-informations');
    }
}
