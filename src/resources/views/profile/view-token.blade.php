<x-jet-action-section>
    <x-slot name="title">
        {{ __('Token administration') }}
    </x-slot>

    <x-slot name="description">
        {{ __('View your personal API token.') }}
    </x-slot>

    <x-slot name="content">
        <div class="max-w-xl text-sm text-gray-600">
            {{ __('If necessary, you can view your personal API token. It is used in your Android App to authenticate you.') }}
        </div>

        <div class="flex items-center mt-5">
            <x-jet-button wire:click="confirmViewToken" wire:loading.attr="disabled">
                {{ __('Log Out Other Browser Sessions') }}
            </x-jet-button>
        </div>

        <!-- Log Out Other Devices Confirmation Modal -->
        <x-jet-dialog-modal wire:model="confirmingLogout">
            <x-slot name="title">
                {{ __('View your API Token') }}
            </x-slot>

            <x-slot name="content">
                {{ __('Please enter your password to confirm your identity.') }}

                <div class="mt-4" x-data="{}" x-on:confirming-logout-other-browser-sessions.window="setTimeout(() => $refs.password.focus(), 250)">
                    <x-jet-input type="password" class="mt-1 block w-3/4"
                                 placeholder="{{ __('Password') }}"
                                 x-ref="password"
                                 wire:model.defer="password"
                                 wire:keydown.enter="ViewToken" />

                    <x-jet-input-error for="password" class="mt-2" />
                </div>
            </x-slot>

            <x-slot name="footer">
                <x-jet-secondary-button wire:click="$toggle('confirmingViewToken')" wire:loading.attr="disabled">
                    {{ __('Cancel') }}
                </x-jet-secondary-button>

                <x-jet-button class="ml-2"
                              wire:click="confirmViewToken"
                              wire:loading.attr="disabled">
                    {{ __('View') }}
                </x-jet-button>
            </x-slot>
        </x-jet-dialog-modal>
    </x-slot>
</x-jet-action-section>








<!-- Token -->
<div class="col-span-6 sm:col-span-4">
    <x-jet-label for="token" value="{{ __('Token') }}" />
    @php
        $decrypt_token=str_replace(md5($this->user->id), "", Crypt::decryptString($this->user->token));
    @endphp
    <x-jet-button wire:click="confirmViewToken" wire:loading.attr="disabled">
        {{ __('View token') }}
    </x-jet-button>
    <x-jet-dialog-modal wire:model="confirmViewToken">
        <x-slot name="title">
            {{ __('View token') }}
        </x-slot>

        <x-slot name="content">
            {{ __('Are you sure you want to delete your account? Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.') }}


        </x-slot>

        <x-slot name="footer">
            <x-jet-secondary-button wire:click="$toggle('confirmingUserDeletion')" wire:loading.attr="disabled">
                {{ __('Cancel') }}
            </x-jet-secondary-button>
        </x-slot>
    </x-jet-dialog-modal>
</div>
