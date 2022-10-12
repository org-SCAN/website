<?php

namespace App\Http\Livewire;


use Illuminate\Support\Collection;
use Livewire\Component;

class DyanmicInputs extends Component
{
    // DynamicInputs.php

    public Collection $inputs;

    /**
     * Taking advantage of Laravel Collections, we are simply
     * pushing an array with a key of email and an empty string
     * value to the inputs collection.
     * This method will be called when we click the add input link.
     * @return void
     */
    public function addInput()
    {
        $this->inputs->push(['email' => '']);
    }

    /**
     * Again, I'm using Laravel Collections here and I am using
     * the pull method to remove the array with the specified key.
     * This will be called when we click the remove input link.
     * @param $key
     * @return void
     */
    public function removeInput($key)
    {
        $this->inputs->pull($key);
    }

    /**
     * @return void
     */
    public function mount()
    {
        $this->fill([
            'inputs' => collect([['email' => '']]),
        ]);
    }

    public function render()
    {
        return view('livewire.dyanmic-inputs');
    }
}
