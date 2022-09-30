<x-jet-action-section>
    <x-slot name="title">
        {{ __('Request role') }}
    </x-slot>

    <x-slot name="description">
        {{ __('Request a new role') }}
    </x-slot>

    <x-slot name="content">
        <div class="max-w-xl text-sm text-gray-600">
            {{ __('If necessary, you can ask for a new role. Your request need to be accepted by an admin.') }}
            @if($errors->any())
                {!! implode('', $errors->all('<span class="text text-danger"><br>:message</span>')) !!}
            @endif
        </div>
        <div class="flex items-center mt-4">
            @if(empty($request))
                <form class="inline-block" action="{{route('user.request_role',  $user->id)}}" method="POST">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    @livewire("select-dropdown", ['label' => 'role', 'placeholder' => "-- Select the role --", 'datas'
                    =>
                    array_column($roles->toArray() , 'role', 'id'), "selected_value"=>$user->role->id])
                    @stack('scripts')
                    <button
                        class="inline-flex items-center px-4 py-2 mt-4 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray disabled:opacity-25 transition ease-in-out duration-150">
                        Request the role
                    </button>
                </form>
            @else
                {{ __('Your request for the '.$request->role.' role has been sent, please wait for admin response.') }}

            @endif
        </div>
    </x-slot>
</x-jet-action-section>
