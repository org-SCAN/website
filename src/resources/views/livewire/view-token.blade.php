<x-action-section>
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
            <x-button wire:click="confirmViewToken" wire:loading.attr="disabled">
                {{ __('livewire/view-token.get_my_private_api_token') }}
            </x-button>
        </div>

        <!-- Log Out Other Devices Confirmation Modal -->
        <x-dialog-modal wire:model.live="confirmingViewToken">
            <x-slot name="title">
                {{ __('livewire/view-token.get_my_api_token') }}
            </x-slot>
            <x-slot name="content">
                @if(!$this->showToken)
                    {{ __('livewire/view-token.enter_password_to_confirm_identity') }}
                    <div class="mt-4" x-data="{}" x-on:confirming-display-view-token.window="setTimeout(() => $refs.password.focus(), 250)">
                        <x-input type="password" class="mt-1 block w-3/4"
                                     placeholder="{{ __('livewire/view-token.password_placeholder') }}"
                                     x-ref="password"
                                     wire:model="password"
                                     wire:keydown.enter="DisplayViewToken" />

                        <x-input-error for="password" class="mt-2" />
                    </div>
                @else
                    <x-label for="token">{{ __('livewire/view-token.copy_your_personal_token') }}</x-label>
                    <x-input id="token" type="text" class="mt-1 block w-3/4"
                                 value="{{$this->userToken}}"
                                 disabled />
                @endif
            </x-slot>
            <x-slot name="footer">
                @if(!$this->showToken)
                    <x-button class="ml-2"
                                  wire:click="DisplayViewToken"
                                  wire:loading.attr="disabled">
                        {{ __('livewire/view-token.confirm') }}
                    </x-button>
                @endif
                <x-secondary-button wire:click="$toggle('confirmingViewToken')" wire:loading.attr="disabled">
                    {{ __('livewire/view-token.close') }}
                </x-secondary-button>
            </x-slot>
        </x-dialog-modal>
    </x-slot>
</x-action-section>
