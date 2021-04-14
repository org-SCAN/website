<?php

namespace App\Http\Livewire;

use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Jenssegers\Agent\Agent;
use Livewire\Component;

class ViewToken extends Component
{
    public $user;

    /**
     * Indicates if logout is being confirmed.
     *
     * @var bool
     */
    public $confirmingViewToken = false;

    /**
     * The user's current password.
     *
     * @var string
     */
    public $password = '';

    /**
     * Confirm that the user would like to log out from other browser sessions.
     *
     * @return void
     */
    public function confirmViewToken()
    {
        $this->password = '';

        $this->dispatchBrowserEvent('confirming-ViewToken');

        $this->confirmingViewToken = true;
    }
    /**
     * Log out from other browser sessions.
     *
     * @param  \Illuminate\Contracts\Auth\StatefulGuard  $guard
     * @return void
     */
    public function DisplayViewToken(StatefulGuard $guard)
    {
        $this->resetErrorBag();

        if (! Hash::check($this->password, Auth::user()->password)) {
            throw ValidationException::withMessages([
                'password' => [__('This password does not match our records.')],
            ]);
        }

        $guard->user();
        $this->confirmingViewToken = true;

        $this->emit('DisplayToken');
    }
    public function render()
    {
        $user =Auth::user();
        return view('livewire.view-token')->with(['user' => $user]);
    }
}
