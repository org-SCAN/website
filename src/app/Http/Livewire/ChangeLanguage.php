<?php

namespace App\Http\Livewire;

use App\Models\Language;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ChangeLanguage extends Component
{
    public function render()
    {
        $user = Auth::user();
        $languages = Language::all()->pluck('language_name', 'id');
        return view('livewire.change_language', compact('languages', 'user'));
    }
}