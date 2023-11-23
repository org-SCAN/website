<div>
    @if(1==0)
        <!-- Generate API Token -->
        <x-jet-form-section submit="createApiToken">
            <x-slot name="title">
                {{ __('api/api-token-manager.create_api_token') }}
            </x-slot>

            <x-slot name="description">
                {{ __('api/api-token-manager.api_tokens_allow') }}
            </x-slot>

            <x-slot name="form">
                <!-- Token Name -->
                <div class="col-span-6 sm:col-span-4">
                    <x-jet-label for="name" value="{{ __('api/api-token-manager.token_name') }}" />
                    <x-jet-input id="name" type="text" class="mt-1 block w-full" wire:model.defer="createApiTokenForm.name" autofocus />
                    <x-jet-input-error for="name" class="mt-2" />
                </div>

                <!-- Token Permissions -->
                @if (Laravel\Jetstream\Jetstream::hasPermissions())
                    <div class="col-span-6">
                        <x-jet-label for="permissions" value="{{ __('api/api-token-manager.permissions') }}" />

                        <div class="mt-2 grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach (Laravel\Jetstream\Jetstream::$permissions as $permission)
                                <label class="flex items-center">
                                    <x-jet-checkbox wire:model.defer="createApiTokenForm.permissions" :value="$permission"/>
                                    <span class="ml-2 text-sm text-gray-600">{{ $permission }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                @endif
            </x-slot>

            <x-slot name="actions">
                <x-jet-action-message class="mr-3" on="created">
                    {{ __('api/api-token-manager.created') }}
                </x-jet-action-message>

                <x-jet-button>
                    {{ __('api/api-token-manager.create') }}
                </x-jet-button>
            </x-slot>
        </x-jet-form-section>

    @if ($this->user->tokens->isNotEmpty())
        <x-jet-section-border />

        <!-- Manage API Tokens -->
        <div class="mt-10 sm:mt-0">
            <x-jet-action-section>
                <x-slot name="title">
                    {{ __('api/api-token-manager.manage_api_tokens') }}
                </x-slot>

                <x-slot name="description">
                    {{ __('api/api-token-manager.delete_any_tokens') }}
                </x-slot>

                <!-- API Token List -->
                <x-slot name="content">
                    <div class="space-y-6">
                        @foreach ($this->user->tokens->sortBy('name') as $token)
                            <div class="flex items-center justify-between">
                                <div>
                                    {{ $token->name }}
                                </div>

                                <div class="flex items-center">
                                    @if ($token->last_used_at)
                                        <div class="text-sm text-gray-400">
                                            {{ __('api/api-token-manager.last_used') }} {{ $token->last_used_at->diffForHumans() }}
                                        </div>
                                    @endif

                                    @if (Laravel\Jetstream\Jetstream::hasPermissions())
                                        <button class="cursor-pointer ml-6 text-sm text-gray-400 underline" wire:click="manageApiTokenPermissions({{ $token->id }})">
                                            {{ __('api/api-token-manager.permissions') }}
                                        </button>
                                    @endif

                                        <!--
                                    <button class="cursor-pointer ml-6 text-sm text-red-500" wire:click="confirmApiTokenDeletion({{ $token->id }})">
                                        {{ __('api/api-token-manager.delete') }}
                                        </button> -->
                                </div>
                            </div>
                        @endforeach
                    </div>
                </x-slot>
            </x-jet-action-section>
        </div>
    @endif

    <!-- Token Value Modal -->
    <x-jet-dialog-modal wire:model="displayingToken">
        <x-slot name="title">
            {{ __('api/api-token-manager.api_token') }}
        </x-slot>

        <x-slot name="content">
            <div>
                {{ __('api/api-token-manager.copy_new_api_token') }}
            </div>

            <x-jet-input x-ref="plaintextToken" type="text" readonly :value="$plainTextToken"
                class="mt-4 bg-gray-100 px-4 py-2 rounded font-mono text-sm text-gray-500 w-full"
                autofocus autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false"
                @showing-token-modal.window="setTimeout(() => $refs.plaintextToken.select(), 250)"
            />
        </x-slot>

        <x-slot name="footer">
            <x-jet-secondary-button wire:click="$set('displayingToken', false)" wire:loading.attr="disabled">
                {{ __('api/api-token-manager.close') }}
            </x-jet-secondary-button>
        </x-slot>
    </x-jet-dialog-modal>

    <!-- API Token Permissions Modal -->
    <x-jet-dialog-modal wire:model="managingApiTokenPermissions">
        <x-slot name="title">
            {{ __('api/api-token-manager.api_token_permissions') }}
        </x-slot>

        <x-slot name="content">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach (Laravel\Jetstream\Jetstream::$permissions as $permission)
                    <label class="flex items-center">
                        <x-jet-checkbox wire:model.defer="updateApiTokenForm.permissions" :value="$permission"/>
                        <span class="ml-2 text-sm text-gray-600">{{ $permission }}</span>
                    </label>
                @endforeach
            </div>
        </x-slot>

        <x-slot name="footer">
            <x-jet-secondary-button wire:click="$set('managingApiTokenPermissions', false)" wire:loading.attr="disabled">
                {{ __('api/api-token-manager.cancel') }}
            </x-jet-secondary-button>

            <x-jet-button class="ml-2" wire:click="updateApiToken" wire:loading.attr="disabled">
                {{ __('api/api-token-manager.save') }}
            </x-jet-button>
        </x-slot>
    </x-jet-dialog-modal>

    <!-- Delete Token Confirmation Modal -->
    <x-jet-confirmation-modal wire:model="confirmingApiTokenDeletion">
        <x-slot name="title">
            {{ __('api/api-token-manager.delete_api_token') }}
        </x-slot>

        <x-slot name="content">
            {{ __('api/api-token-manager.delete_api_token_confirm') }}
        </x-slot>

        <x-slot name="footer">
            <x-jet-secondary-button wire:click="$toggle('confirmingApiTokenDeletion')" wire:loading.attr="disabled">
                {{ __('api/api-token-manager.cancel') }}
            </x-jet-secondary-button>

            <x-jet-danger-button class="ml-2" wire:click="deleteApiToken" wire:loading.attr="disabled">
                {{ __('api/api-token-manager.delete') }}
            </x-jet-danger-button>
        </x-slot>
    </x-jet-confirmation-modal>
</div>
