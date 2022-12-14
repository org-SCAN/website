<?php

namespace App\Http\Livewire;


class CreateFormChooseField extends Forms\Form
{
    public $rangeable = false;

    // get the selected field from the dropdown
    public function isRangeable($field)
    {
        $this->rangeable = $field->dataType->rangeable;
    }

    public function render()
    {
        return view('livewire.create-form-choose-field');
    }
}
