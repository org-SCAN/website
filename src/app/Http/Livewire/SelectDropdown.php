<?php

namespace App\Http\Livewire;

use Livewire\Component;

class SelectDropdown extends Component
{

    public $selected = '';
    public $datas;
    public $label;
    public $placeholder;
    public $selected_value;


    public function render()
    {
        $datas = $this->datas;
        $label = $this->label;
        $placeholder = $this->placeholder;
        $selected_value = $this->selected_value;
        return view('livewire.select-dropdown', compact('datas', "label", "placeholder", "selected_value"))->extends('layouts.app');
    }
}
