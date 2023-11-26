<x-jet-action-section>
    <x-slot name="title">
        {{ __('livewire/change_crew.change_team') }}
    </x-slot>

    <x-slot name="description">
        {{ __('livewire/change_crew.change_team_description') }}
    </x-slot>

    <x-slot name="content">
        <div class="max-w-xl text-sm text-gray-600">
            {!! __('livewire/change_crew.change_team_info', ['team' => $user->crew->name]) !!}
        </div>
        <div class="flex items-center mt-4">
                <form class="inline-block" action="{{route('user.change_team',  $user->id)}}" method="POST">
                     <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        @livewire("forms.form", [
                            "form_elem" => "name",
                            "type" => "select-dropdown",
                            "title" => __("livewire/change_crew.form_title"),
                            "placeHolder" => __("livewire/change_crew.form_placeholder"),
                            "associated_list" =>  array_column($crews->toArray() , 'name', 'id'),
                            "previous" => $user->crew->id
                            ])



                    <button
                        class="inline-flex items-center px-4 py-2 mt-4 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray disabled:opacity-25 transition ease-in-out duration-150">
                        {{ __('livewire/change_crew.change_team') }}
                    </button>
                </form>
        </div>
    </x-slot>
</x-jet-action-section>
