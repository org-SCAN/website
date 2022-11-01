<?php

namespace App\Http\Livewire;

use Livewire\Component;

class FormText extends Component
{

    public $form_elem;
    public $title;
    public $placeHolder;
    public $hint;
    public $previous;

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
