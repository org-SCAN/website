<?php

namespace App\Livewire;

use Livewire\Component;

class LinkSelectDropdown extends Component
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
        return view('livewire.link-select-dropdown', compact('datas', "label", "placeholder", "selected_value"))->extends('layouts.app');
    }
}
