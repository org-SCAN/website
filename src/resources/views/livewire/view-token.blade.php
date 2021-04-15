<x-jet-action-section>
    <x-slot name="title">
        {{ __('Token administration') }}
    </x-slot>

    <x-slot name="description">
        {{ __('Get your personal API token.') }}
    </x-slot>

    <x-slot name="content">
        <div class="max-w-xl text-sm text-gray-600">
            {{ __('If necessary, you can view your personal API token. It is used in your Android App to authenticate you.') }}
        </div>

        <div class="flex items-center mt-5">
            <x-jet-button wire:click="confirmViewToken" wire:loading.attr="disabled">
                {{ __('Get my private API token') }}
            </x-jet-button>
        </div>

        <!-- Log Out Other Devices Confirmation Modal -->
        <x-jet-dialog-modal wire:model="confirmingViewToken">
            <x-slot name="title">
                {{ __('Get my API Token') }}
            </x-slot>
                <x-slot name="content">
                    @if(!$this->showToken)
                    {{ __('Please enter your password to confirm your identity.') }}
                    <div class="mt-4" x-data="{}" x-on:confirming-display-view-token.window="setTimeout(() => $refs.password.focus(), 250)">
                        <x-jet-input type="password" class="mt-1 block w-3/4"
                                     placeholder="{{ __('Password') }}"
                                     x-ref="password"
                                     wire:model.defer="password"
                                     wire:keydown.enter="DisplayViewToken" />

                        <x-jet-input-error for="password" class="mt-2" />
                    </div>
                    @else
                        <x-jet-label for="token">Please copy your personal token</x-jet-label>
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
                    {{ __('Confirm') }}
                </x-jet-button>
                @endif
                <x-jet-secondary-button wire:click="$toggle('confirmingViewToken')" wire:loading.attr="disabled">
                    {{ __('Close') }}
                </x-jet-secondary-button>
            </x-slot>
        </x-jet-dialog-modal>
    </x-slot>
</x-jet-action-section>
