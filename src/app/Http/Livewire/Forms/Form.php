<?php

namespace App\Http\Livewire\Forms;
use App\Models\ListControl;
use Illuminate\Support\Str;
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
    public $associated_list;

    public function render()
    {
        // check if associated list is set and if it is an uuid
        if (isset($this->associated_list) && Str::isUuid($this->associated_list)) {
            // get the list
            $list = ListControl::find($this->associated_list);
            // check if the list is found
            if ($list) {
                // get the list items
                $this->associated_list = $list->getListDisplayedValue();
            }
        }
        try {
            return view("livewire.forms.form-" . $this->type)->extends('layouts.app');
        } catch (Throwable $th) {
            report($th);
            return false;
        }
    }

}
