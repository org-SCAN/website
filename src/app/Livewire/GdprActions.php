<?php

namespace App\Livewire;

use App\Models\Link;
use App\Models\Refugee;
use Illuminate\Http\RedirectResponse;
use App\Services\ZipService;
use Illuminate\Support\Str;
use Livewire\Component;
use ZipArchive;

class GdprActions extends Component
{
    public $persons;
    public $selected = null;
    public $person = null;
    //boot
    public function mount(): void {
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

        // Search in links
        $links = Link::withTrashed()->where('from', $this->person->id)->orWhere('to', $this->person->id)->get();
        foreach($links as $link){
            $link->forceDelete();
        }
        $this->person->forceDelete();

        return redirect()->to('/user/profile');
    }

    /**
     * This function is used to get all the personal details of a person and return them in a zip folder
     * There is one file for personal detail, one file for relations (link)
     */
    public function export(ZipService $zipService)
    {

        $links = Link::withTrashed()->where('from', $this->person->id)
            ->orWhere('to', $this->person->id)
            ->get();

        //make sure the zip directory exists
        if (!file_exists('zip')) {
            mkdir('zip', 0777, true);
        }

        $zipFileName = 'zip/' . Str::snake($this->person->best_descriptive_value) . '.zip';
        // Utiliser le service Zip
        $zipService->open($zipFileName, ZipArchive::CREATE);
        $zipService->addFromString('personal_details.txt', $this->person->toJson());
        $zipService->addFromString('links.txt', $links->toJson());
        $zipService->close();

        $response = response()->download($zipFileName);
        if (app()->environment() !== 'testing') {
            $response->deleteFileAfterSend(true);
        }
        return $response;
    }
}
