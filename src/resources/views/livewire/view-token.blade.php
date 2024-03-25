<x-jet-action-section>
    <x-slot name="title">
        {{ __('livewire/view-token.token_administration') }}
    </x-slot>

    <x-slot name="description">
        {{ __('livewire/view-token.get_your_personal_api_token') }}
    </x-slot>

    <x-slot name="content">
        <div class="max-w-xl text-sm text-gray-600">
            {{ __('livewire/view-token.view_personal_api_token') }}
        </div>

        <div class="flex items-center mt-5">
            <x-jet-button wire:click="confirmViewToken" wire:loading.attr="disabled">
                {{ __('livewire/view-token.get_my_private_api_token') }}
            </x-jet-button>
        </div>

        <!-- Log Out Other Devices Confirmation Modal -->
        <x-jet-dialog-modal wire:model="confirmingViewToken">
            <x-slot name="title">
                {{ __('livewire/view-token.get_my_api_token') }}
            </x-slot>
            <x-slot name="content">
                @if(!$this->showToken)
                    {{ __('livewire/view-token.enter_password_to_confirm_identity') }}
                    <div class="mt-4" x-data="{}" x-on:confirming-display-view-token.window="setTimeout(() => $refs.password.focus(), 250)">
                        <x-jet-input type="password" class="mt-1 block w-3/4"
                                     placeholder="{{ __('livewire/view-token.password_placeholder') }}"
                                     x-ref="password"
                                     wire:model.defer="password"
                                     wire:keydown.enter="DisplayViewToken" />

                        <x-jet-input-error for="password" class="mt-2" />
                    </div>
                @else
                    <x-jet-label for="token">{{ __('livewire/view-token.copy_your_personal_token') }}</x-jet-label>
                    <x-jet-input id="token" type="text" class="mt-1 block w-3/4"
                                 value="{{$this->userToken}}"
                                 disabled />
                @endif
            </x-slot>
            <x-slot name="footer">
                @if(!$this->showToken)
                    <x-jet-button class="ml-2"
                                  wire:click="DisplayViewToken"
                                  wire:loading.attr="disabled">
                        {{ __('livewire/view-token.confirm') }}
                    </x-jet-button>
                @endif
                <x-jet-secondary-button wire:click="$toggle('confirmingViewToken')" wire:loading.attr="disabled">
                    {{ __('livewire/view-token.close') }}
                </x-jet-secondary-button>
            </x-slot>
        </x-jet-dialog-modal>
    </x-slot>
</x-jet-action-section>
