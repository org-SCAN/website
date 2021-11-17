<?php

namespace App\Http\Livewire;

use App\Models\Crew;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ChangeCrew extends Component
{
    public function render()
    {
        $user = Auth::user();
        $crews = Crew::all();
        return view('livewire.change_crew', compact('crews', 'user'));
    }
}
