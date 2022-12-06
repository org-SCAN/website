<?php

namespace App\Http\Livewire;

use App\Models\ListDataType;
use Livewire\Component;

class CreateList extends Component
{
    public $fields = [''];
    public function mount()
    {
        $this->data_types = ListDataType::list();
    }
    public function render()
    {
        return view('livewire.create-list');
    }

    public function addField()
    {
        $this->fields[] = '';
    }

    public function removeField($index)
    {
        unset($this->fields[$index]);
        $this->fields = array_values($this->fields);
    }
}
