<?php

namespace App\Http\Livewire\Forms;

use Livewire\Component;
use Illuminate\Support\Facades\Log;

class FormDropdown extends Component
{
    // Data formatting example for the dropdown
    //It needs to be the same
    public $parent_list = [
        '1' => 'Parent 1',
        '2' => 'Parent 2',
        '3' => 'Parent 3',
    ];
    public $child_data = [
        '1' => [
            '1' => 'Child 1',
            '2' => 'Child 2',
            '3' => 'Child 3',
        ],
        '2' => [
            '4' => 'Child 4',
            '5' => 'Child 5',
            '6' => 'Child 6',
        ],
        '3' => [
            '7' => 'Child 7',
            '8' => 'Child 8',
            '9' => 'Child 9',
        ],
    ];
    public $selected_parent_id;
    public $childs = [];
    public $child_id;

    public function render()
    {
        if($this->selected_parent_id){
            $this->childs = $this->child_data[$this->selected_parent_id];
        }

        return view('livewire.form-dropdown')->extends('layouts.app');
    }
}
