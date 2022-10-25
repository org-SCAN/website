<?php

namespace App\Http\Livewire;

use App\Models\Field;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ShowFields extends Component
{
    public function render()
    {
        $user_crew = Auth::user()->crew->id;
        $fields = Field::where("crew_id", $user_crew)
            ->orderBy("required")
            ->orderBy("order")
            ->get();
        return view('livewire.show-fields', compact('fields'));
    }

    public function updateOrder($list){
        foreach ($list as $item){
            Field::find($item['value'])->update(["order"=>$item['order']]);
        }
    }
}
