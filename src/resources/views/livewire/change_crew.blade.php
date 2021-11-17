<x-jet-action-section>
    <x-slot name="title">
        {{ __('Change team') }}
    </x-slot>

    <x-slot name="description">
        {{ __('Request a team') }}
    </x-slot>

    <x-slot name="content">
        <div class="max-w-xl text-sm text-gray-600">
            {{ __('If necessary, you can change your team.') }}
        </div>
        <div class="flex items-center mt-4">
                <form class="inline-block" action="{{route('user.change_team',  $user->id)}}" method="POST">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    @livewire("select-dropdown", ['label' => 'crew', 'placeholder' => "--Select the team --", 'datas' =>
                    array_column($crews->toArray() , 'name', 'id'), "selected"=>$user->getCurrentTeamId()])
                    @stack('scripts')
                    <button
                        class="inline-flex items-center px-4 py-2 mt-4 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray disabled:opacity-25 transition ease-in-out duration-150">
                         Change team
                    </button>
                </form>
        </div>
    </x-slot>
</x-jet-action-section>
