<?php

namespace App\Livewire\Forms;

use App\Models\Field;
use Livewire\Component;
use Throwable;


class Show extends Component
{
    public Field $field;
    public function render()
    {
        try {
            return view('livewire.forms.show.'.$this->field->dataType->html_type)->extends('layouts.app');
        } catch (Throwable $th) {
            return view('livewire.forms.show.text')->extends('layouts.app');
        }
    }
}
