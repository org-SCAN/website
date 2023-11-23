@section('title', __('crew/edit.edit_team_details', ['team_name' => $crew->name]))

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('crew/edit.edit') }} : <strong>{{ $crew->name }}</strong>
        </h2>
        <!-- Include Bootstrap CSS -->
    </x-slot>

    <div>
        <div class="max-w-4xl mx-auto py-10 sm:px-6 lg:px-8">
            <div class="block mb-8">
                <a href="{{ route('crew.index') }}"
                   class="bg-gray-300 hover:bg-gray-400 text-black font-bold py-2 px-4 rounded">
                    {{ __('crew/edit.back_to_list') }}
                </a>
                <form class="inline-block" action="{{ route('crew.destroy', $crew->id) }}" method="POST"
                      onsubmit="return confirm('{{ __('crew/edit.delete_confirm') }}');">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="flex-shrink-0 bg-red-200 hover:bg-red-300 text-black font-bold py-2 px-4 rounded">
                        {{ __('crew/edit.delete') }}
                    </button>
                </form>
            </div>
            <div class="mt-5 md:mt-0 md:col-span-2">
                <form method="post" action="{{ route('crew.update', $crew->id) }}">
                    @csrf
                    @method('put')
                    <div class="shadow overflow-hidden sm:rounded-md">
                        <div class="px-4 py-5 bg-white sm:p-6">
                            <label for="name" class="block font-medium text-sm text-gray-700"> {{ __('crew/edit.name') }}</label>
                            <input type="text" name="name" id="name"
                                   class="form-input rounded-md shadow-sm mt-1 block w-full"
                                   value="{{ old('name', $crew->name) }}"/>
                            @error('name')
                                <p class="text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center justify-end px-4 py-3 bg-gray-50 text-right sm:px-6">
                            <button
                                class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent
                                rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700
                                active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray
                                disabled:opacity-25 transition ease-in-out duration-150">
                                {{ __('crew/edit.save') }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
