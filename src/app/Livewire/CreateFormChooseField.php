<?php

namespace App\Livewire;


use App\Models\ListDataType;

class CreateFormChooseField extends Forms\Form
{
    public  $selectedField = null;
    public $isList = false;
    // get the selected field from the dropdown
    public function updatedSelectedField()
    {
        // get selected field
        $this->dataType = ListDataType::find($this->selectedField) ?? null;
        if($this->dataType){
            $this->rangeable = $this->dataType->rangeable ?? false;
            $this->isList = $this->dataType->name == 'List' ?? false;
        }else{
            $this->rangeable = false;
            $this->isList = false;
        }
    }

    public function render()
    {
        return view('livewire.create-form-choose-field');
    }
}
