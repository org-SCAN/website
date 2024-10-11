<x-action-section>
    <x-slot name="title">
        {{ __("livewire/gdpr-actions.gdpr_actions")  }}
    </x-slot>

    <x-slot name="description">
        {{ __("livewire/gdpr-actions.description")  }}
    </x-slot>

    <x-slot name="content">
        <div>
            @if(!$this->person)
                {{-- Afficher un menu déroulant avec tous les éléments enregistrés, connecté à la personne --}}
                <x-label for="person" value="{{ __("livewire/gdpr-actions.form_title")  }}"/>
                <select wire:model.live="person" class="form-input rounded-md shadow-sm mt-1 block w-full" id="person"
                        name="person">
                    <option value="">{{ __("livewire/gdpr-actions.form_placeholder")  }}</option>
                    @foreach($this->persons as $person)
                        <option value="{{ $person->id }}">{{ $person->best_descriptive_value }}</option>
                    @endforeach
                </select>
            @else
                {{-- Afficher les informations de la personne --}}
                {{ $this->person->best_descriptive_value }}
                <div class="mt-2">
                    {{-- Les boutons Exporter et Supprimer apparaîtront si une personne est sélectionnée --}}
                    <x-button wire:click="export" wire:loading.attr="disabled">
                        {{ __("livewire/gdpr-actions.export")  }}
                    </x-button>
                    <x-button wire:click="delete" wire:loading.attr="disabled">
                        {{ __("livewire/gdpr-actions.delete")  }}
                    </x-button>
                </div>
            @endif
        </div>
    </x-slot>
</x-action-section>
