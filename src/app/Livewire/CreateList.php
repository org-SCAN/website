<?php

namespace App\Livewire;

use App\Models\ListDataType;
use Illuminate\Support\Collection;
use JetBrains\PhpStorm\NoReturn;
use Livewire\Component;

class CreateList extends Component
{
    public $fields = [];
    public $data_types = [];

    public function mount(): void {
        // Initialize the fields and data types
        $this->fields = [
            [
                'name' => '',
                'data_type_id' => '',
                'required' => false
            ],
            // Initialize with one empty field
        ];
        // Fetch the available data types from the database
        $this->data_types = ListDataType::list();
    }

    public function render(): \Illuminate\View\View {
        return view('livewire.create-list');
    }

//    public function addField()
//    {
//        $this->fields[] = ['name' => '', 'data_type_id' => '', 'required' => false];
//    }

    public function addField(): void {
        $this->fields[] = [
            'name' => '',
            'data_type_id' => '',
            'required' => false
        ];
    }

    public function removeField($index): void {
        unset($this->fields[$index]);
        $this->fields = array_values($this->fields);
    }
}
