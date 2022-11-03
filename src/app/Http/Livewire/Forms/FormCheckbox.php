<?php

namespace App\Http\Livewire\Forms;

use App\Http\Livewire\forms\Form;

class FormCheckbox extends Form
{

    public function render()
    {
        return view('livewire.forms.form-checkbox')->extends('layouts.app');
    }
}