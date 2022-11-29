<?php

namespace App\Http\Livewire\Forms;

use Livewire\Component;

class Form extends Component
{

    public $form_elem;
    public $title;
    public $placeHolder;
    public $hint;
    public $previous;
    public $warning;
    public $type;

    public $lat;
    public $long;

    public function mount(){
        $this->lat = "0";
        $this->long = "0";
    }

    public function render()
    {
        try {
            return view("livewire.forms.form-" . $this->type)->extends('layouts.app');
        } catch (\Throwable $th) {
            report($th);
            return false;
        }
    }

}
