<?php

namespace App\Http\Livewire;

use App\Models\Field;
use Livewire\Component;

class ShowFields extends Component
{
    public function render()
    {
        $fields = Field::where("deleted", 0)
            ->orderBy("order")
            ->orderBy("required")
            ->get();
        return view('livewire.show-fields', compact('fields'));
    }

    public function updateOrder($list){
        foreach ($list as $item){
            Field::find($item['value'])->update(["order"=>$item['order']]);
        }
    }
}
