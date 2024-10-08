@section('title', __("lists_control/define_displayed_value.title"))
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __("lists_control/define_displayed_value.section_title", ["list_title" => $listControl->title]) }}
        </h2>
    </x-slot>
    <div>
        <div class="max-w-4xl mx-auto py-10 sm:px-6 lg:px-8">
            <div class="block mb-8">
                <p>
                    {{ __("lists_control/define_displayed_value.description") }}
                </p>
            </div>

            <div class="mt-5 md:mt-0 md:col-span-2">
                <form method="post" action="{{ route('lists_control.store_displayed_value', $listControl) }}">
                    @csrf
                    <div class="shadow overflow-hidden sm:rounded-md">

                        <div class="px-4 py-5 bg-white sm:p-6">
                            @php($form_elem = "displayed_value")
                            <label for="{{$form_elem}}" class="block font-medium text-md text-gray-700">
                                {{ __("lists_control/define_displayed_value.displayed_value") }}
                            </label>

                            @php( $list = array_column($fields->toArray(), 'field', 'id'))
                            <x-form-select name="{{$form_elem}}" :options="$list" id="{{$form_elem}}"
                                           class="form-input rounded-md shadow-sm mt-1 block w-full"/>
                            <small id="{{$form_elem}}Help" class="block font-medium text-sm text-gray-500 ">
                                {{ __("lists_control/define_displayed_value.displayed_value_hint") }}
                            </small>

                            @error($form_elem)
                            <p class="text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center justify-end px-4 py-3 bg-gray-50 text-right sm:px-6">
                            <button
                                class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray disabled:opacity-25 transition ease-in-out duration-150">
                                {{ __("lists_control/define_displayed_value.save") }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
