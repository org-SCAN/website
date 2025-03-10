<x-action-section>
    <x-slot name="title">
        {{ __('profile/delete-user-form.delete_account') }}
    </x-slot>

    <x-slot name="description">
        {{ __('profile/delete-user-form.permanently_delete_your_account') }}
    </x-slot>

    <x-slot name="content">
        <div class="max-w-xl text-sm text-gray-600">
            {{ __('profile/delete-user-form.delete_account_warning') }}
        </div>

        <div class="mt-5">
            <x-danger-button wire:click="confirmUserDeletion" wire:loading.attr="disabled">
                {{ __('profile/delete-user-form.delete_account') }}
            </x-danger-button>
        </div>

        <!-- Delete ManageUsersController Confirmation Modal -->
        <x-dialog-modal wire:model.live="confirmingUserDeletion">
            <x-slot name="title">
                {{ __('profile/delete-user-form.delete_account') }}
            </x-slot>

            <x-slot name="content">
                {{ __('profile/delete-user-form.confirm_delete_account') }}

                <div class="mt-4" x-data="{}" x-on:confirming-delete-user.window="setTimeout(() => $refs.password.focus(), 250)">
                    <x-input type="password" class="mt-1 block w-3/4"
                                 placeholder="{{ __('profile/delete-user-form.password') }}"
                                x-ref="password"
                                wire:model="password"
                                wire:keydown.enter="deleteUser" />

                    <x-input-error for="password" class="mt-2" />
                </div>
            </x-slot>

            <x-slot name="footer">
                <x-secondary-button wire:click="$toggle('confirmingUserDeletion')" wire:loading.attr="disabled">
                    {{ __('profile/delete-user-form.cancel') }}
                </x-secondary-button>

                <x-danger-button class="ml-2" wire:click="deleteUser" wire:loading.attr="disabled">
                    {{ __('profile/delete-user-form.delete_account') }}
                </x-danger-button>
            </x-slot>
        </x-dialog-modal>
    </x-slot>
</x-action-section>
