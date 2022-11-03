<?php

namespace App\Http\Livewire;

use App\Http\Livewire\Form;

class FormCheckbox extends Form
{

    public function render()
    {
        $form_elem = $this->form_elem;
        $title = $this->title;
        $hint = $this->hint;
        $warning = $this->warning;
        $previous = $this->previous;
        return view('livewire.form-checkbox', compact('form_elem', 'title', 'hint', 'warning', 'previous'))->extends('layouts.app');
    }
}
