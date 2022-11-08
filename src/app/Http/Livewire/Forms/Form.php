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

    public function render()
    {
        $returnView = "";
        if($this->type == 'text'){
            $returnView = 'livewire.forms.form-text';
        }elseif($this->type == 'checkbox'){
            $returnView = 'livewire.forms.form-checkbox';
        }elseif($this->type == 'number'){
            $returnView = 'livewire.forms.form-number';
        }elseif($this->type == 'date'){
            $returnView = 'livewire.forms.form-date';
        }
        $returnView = view($returnView)->extends('layouts.app');
        return $returnView;
    }

}
