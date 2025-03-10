<?php

namespace App\Livewire\Forms;
use App\Models\Field;
use App\Models\ListControl;
use Illuminate\Support\Str;
use Livewire\Component;
use Throwable;

class Form extends Component
{
    public $form_elem = null;
    public $title = null;
    public $placeHolder = null;
    public $hint = null;
    public $previous = null;
    public $warning = null;
    public $type = null;
    public $showError = true;
    public $associated_list;
    public $rangeable = null;
    public $field = null;
    public $dataType = null;
    public $fieldCount = 1;

    public function render()
    {

        if ($this->field instanceof Field) {
            $this->title = $this->title ?? $this->field->title;
            $this->placeHolder = $this->placeHolder ?? $this->field->placeholder;
            $this->type = $this->type ?? $this->field->dataType->html_type;
            $this->associated_list = $this->associated_list ?? $this->field->linked_list;
            $this->rangeable = $this->rangeable ?? $this->field->range;
            $this->dataType = $this->dataType ?? $this->field->dataType;
        }
        if ($this->previous && $this->dataType && $this->dataType->model) {
            $this->previous = $this->dataType->model::decode($this->previous);
        }
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
    public function mount()
    {
        if (session()->has('errors')) {
            $this->fieldCount = session()->get('fieldCount', 1);
        }
    }
    public function addField()
    {
        $this->fieldCount++;
        session()->put('fieldCount', $this->fieldCount);
    }
    public function removeField()
    {
        $this->fieldCount--;
        session()->put('fieldCount', $this->fieldCount);
    }
}
