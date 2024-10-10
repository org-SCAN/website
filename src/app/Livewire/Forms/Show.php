<?php

namespace App\Livewire\Forms;

use Livewire\Component;
use Throwable;


class Show extends Component
{
    public $field;
    public function render()
    {
        try {

            return view('livewire.forms.show.'.$this->field->dataType->html_type)->extends('layouts.app');
        } catch (Throwable $th) {
            return view('.text')->extends('layouts.app');
        }
    }
}
