@section('title', __("roles/create.create_new_role"))
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('roles/create.new_role') }}
        </h2>
    </x-slot>
    <div>
        <div class="max-w-4xl mx-auto py-10 sm:px-6 lg:px-8">
            <div class="block mb-8">
                <a href="{{ route('roles.index') }}"
                   class="bg-gray-300 hover:bg-gray-400 text-black font-bold py-2 px-4 rounded">{{ __('common.back') }}</a>
            </div>
            <div class="mt-5 md:mt-0 md:col-span-2">
                <form method="post" action="{{ route('roles.store') }}">
                    @csrf
                    <div class="shadow overflow-hidden sm:rounded-md">
                        <div class="px-4 py-5 bg-white sm:p-6">
                            @livewire("forms.form", [
                                "form_elem" => "role[name]",
                                "type" => "text",
                                "title" => __('roles/create.name'),
                                "previous" => old("role.name", ""),
                            ])
                        </div>

                        <div class="px-4 py-5 bg-white sm:p-6">
                            <a class="block font-medium text-sm text-gray-700">{{ __('roles/create.permissions') }}</a>
                            @foreach ($route_bases as $route_base)
                                <label for="{{ $route_base }}All">
                                    <input name="{{ $route_base }}All" style="margin: 10px" type="checkbox"
                                           id="{{ $route_base }}All" class="{{ $route_base }}All">
                                    <span class="font-bold text-sm text-gray-700">{{ __('roles/create.all') }} {{ $route_base }}</span>
                                </label>
                                <br>
                                @foreach($sorted_permissions[$route_base] as $permission_id => $permission_name)
                                    <label for="{{ $permission_id }}">
                                        <input name="permissions[{{ $permission_id }}]" type="checkbox"
                                               id="{{ $permission_id }}" value="{{ $permission_id }}"
                                               class="{{ $route_base }} m-1 ml-5"
                                            @checked(old("permissions.$permission_id", ''))
                                        >
                                        <span class="font-medium text-sm text-gray-700">{{ $permission_name }}</span>
                                    </label>
                                    <br>
                                    @error($permission_id)
                                    <p class="text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                @endforeach
                                <br>
                            @endforeach
                        </div>

                        <div class="flex items-center justify-end px-4 py-3 bg-gray-50 text-right sm:px-6">
                            <button
                                class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray disabled:opacity-25 transition ease-in-out duration-150">
                                {{ __('common.save') }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        @foreach($route_bases as $route_base)
        $('.{{ $route_base }}All').on('click', function (e) {
            $('.{{ $route_base }}').prop('checked', $(e.target).prop('checked'));
        });
        @endforeach
    </script>

</x-app-layout>
