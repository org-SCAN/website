<?php

namespace App\Http\Livewire\Forms;

use App\Http\Livewire\forms\Form;

class FormNumber extends Form
{
    public function render()
    {
        return view('livewire.forms.form-number')->extends('layouts.app');
    }
}
