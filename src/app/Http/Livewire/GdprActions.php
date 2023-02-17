<?php

namespace App\Http\Livewire;

use App\Models\Link;
use App\Models\Refugee;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use Livewire\Component;
use ZipArchive;

class GdprActions extends Component
{
    public $persons;
    public $selected = null;
    public $person = null;
    //boot
    public function mount()
    {
        $this->persons = Refugee::withTrashed()->get();
    }

    public function render()
    {
        return view('livewire.gdpr-actions');
    }

    public function updatedPerson($value)
    {
        $this->person = Refugee::withTrashed()->find($value);
    }


    /**
     * This function is used to delete a person and all his related data (logs, relations, personal details, ...)
     * @param $id
     * @return RedirectResponse
     */
    public function delete(){

        $this->person->forceDelete();

        // Search in links
        $links = Link::withTrashed()->where('from', $this->person->id)->orWhere('to', $this->person->id)->get();
        foreach($links as $link){
            $link->forceDelete();
        }

        return redirect()->to('/user/profile');
    }

    /**
     * This function is used to get all the personal details of a person and return them in a zip folder
     * There is one file for personal detail, one file for relations (link)
     */
    public function export() {
        $links = Link::withTrashed()->where('from', $this->person->id)
            ->orWhere('to', $this->person->id)
            ->get();

        // Create a zip file
        $zip = new ZipArchive();
        $zip->open('zip/'.Str::snake($this->person->best_descriptive_value).'.zip',
            ZipArchive::CREATE);

        // Add personal details
        $zip->addFromString('personal_details.txt',
            $this->person->toJson());

        // Add links
        $zip->addFromString('links.txt',
            $links->toJson());

        // Close the zip file
        $zip->close();

        // Download the zip file
        return response()->download('zip/'.Str::snake($this->person->best_descriptive_value).'.zip');
    }
}
