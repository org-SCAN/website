<?php

namespace App\Http\Livewire;

use Livewire\Component;

class FormCheckbox extends Component
{

    public $form_elem;
    public $title;
    public $hint;
    public $warning;

    public function render()
    {
        $form_elem = $this->form_elem;
        $title = $this->title;
        $hint = $this->hint;
        $warning = $this->warning;
        return view('livewire.form-checkbox', compact('form_elem', 'title', 'hint', 'warning'))->extends('layouts.app');
    }
}
