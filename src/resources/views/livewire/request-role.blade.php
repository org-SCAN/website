<x-jet-action-section>
    <x-slot name="title">
        {{ __('livewire/request_role.title') }}
    </x-slot>

    <x-slot name="description">
        {{ __('livewire/request_role.description') }}
    </x-slot>

    <x-slot name="content">
        <div class="max-w-xl text-sm text-gray-600">
            {{ __('livewire/request_role.info') }}
            @if($errors->any())
                {!! implode('', $errors->all('<span class="text text-danger"><br>:message</span>')) !!}
            @endif
        </div>
        <div class="flex items-center mt-4">
            @if(empty($request))
                <form class="inline-block" action="{{route('user.request_role',  $user->id)}}" method="POST">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    @livewire("forms.form", [
                           "form_elem" => "role",
                           "type" => "select-dropdown",
                           "title" => __("livewire/request_role.form.title"),
                           "placeHolder" => __("livewire/request_role.form.placeholder"),
                           "associated_list" =>  array_column($roles->toArray() , 'name', 'id'),
                           "previous" => $user->role->id
                           ])
                    <button
                        class="inline-flex items-center px-4 py-2 mt-4 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray disabled:opacity-25 transition ease-in-out duration-150">
                        {{ __('livewire/request_role.button') }}
                    </button>
                </form>
            @else
                {{ __("livewire/request_role.request_pending", ["role_name" => $request->role->name]) }}

            @endif
        </div>
    </x-slot>
</x-jet-action-section>
