<?php

namespace App\Http\Livewire\Forms;

use App\Http\Livewire\forms\Form;

class FormText extends Form
{

    public function render()
    {
        return view('livewire.form-text')->extends('layouts.app');
    }
}
