<?php

namespace App\Http\Livewire;

use App\Http\Livewire\Form;

class FormText extends Form
{

    public function render()
    {
        $form_elem = $this->form_elem;
        $title = $this->title;
        $placeHolder = $this->placeHolder;
        $hint = $this->hint;
        $previous = $this->previous;
        return view('livewire.form-text', compact('form_elem','title', "placeHolder", "hint", "previous"))->extends('layouts.app');
    }
}
