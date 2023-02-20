<x-jet-action-section>
    <x-slot name="title">
        {{ __('GDPR actions') }}
    </x-slot>

    <x-slot name="description">
        {{ __('Download or delete Item\'s informations.') }}
    </x-slot>

    <x-slot name="content">
        <div>
            @if(!$this->person)
                {{-- Display a dropdown with all the registered Items, this dropdown is wired to Item --}}
                <x-jet-label for="person" value="{{ __('Item') }}"/>
                <select wire:model="person" class="form-input rounded-md shadow-sm mt-1 block w-full" id="person"
                        name="person">
                    <option value="">-- Select the Item to delete --</option>
                    @foreach($this->persons as $person)
                        <option value="{{ $person->id }}">{{ $person->best_descriptive_value }}</option>
                    @endforeach
                </select>
            @else
                {{-- Display the Item's informations --}}
                <strong>{{ $this->person->best_descriptive_value }}</strong>
                <div class="mt-2">
                    <x-jet-button wire:click="export" wire:loading.attr="disabled">
                        {{ __('Export') }}
                    </x-jet-button>
                    <x-jet-button wire:click="delete" wire:loading.attr="disabled">
                        {{ __('Delete') }}
                    </x-jet-button>
                </div>
            @endif
        </div>
    </x-slot>
</x-jet-action-section>
