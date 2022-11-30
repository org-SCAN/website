<?php

namespace App\Http\Livewire\Forms;

use Livewire\Component;
use Throwable;

class Form extends Component
{

    public $form_elem;
    public $title;
    public $placeHolder;
    public $hint;
    public $previous;
    public $warning;
    public $type;
    public $showError = true;

    public function render()
    {
        try {
            return view("livewire.forms.form-" . $this->type)->extends('layouts.app');
        } catch (Throwable $th) {
            report($th);
            return false;
        }
    }

}
