@section('title',"Edit ".$lists_control->title)
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Edit : <b>{{$lists_control->title}}</b>
        </h2>
    </x-slot>

    <div>
        <div class="max-w-4xl mx-auto py-10 sm:px-6 lg:px-8">
            <div class="block mb-8">
                <a href="{{URL::previous() }}"
                   class="bg-gray-200 hover:bg-gray-300 text-black font-bold py-2 px-4 rounded">Back</a>
            </div>
            <div class="mt-5 md:mt-0 md:col-span-2">
                <form method="post" action="{{ route('lists_control.update', $lists_control) }}">
                    @csrf
                    @method('PUT')
                    <div class="shadow overflow-hidden sm:rounded-md">
                        <!--  TITLE SECTION  -->
                        <div class="px-4 py-5 bg-white sm:p-6">
                            @php($form_elem = "title")
                            <label for="{{$form_elem}}" class="block font-medium text-md text-gray-700">List's
                                title</label>
                            <input type="text" name="{{$form_elem}}" id="{{$form_elem}}"
                                   value="{{ old($lists_control->{$form_elem}, $lists_control->{$form_elem}) }}"
                                   class="form-input rounded-md shadow-sm mt-1 block w-full" placeholder="short"/>
                            <small id="{{$form_elem}}Help" class="block font-medium text-sm text-gray-500">It'll be
                                shown as title when the list is used.</small>
                            @error($form_elem)
                            <p class="text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!--  LABEL SECTION  -->
                        <div class="px-4 py-5 bg-white sm:p-6">
                            @php($form_elem = "name")
                            <label for="{{$form_elem}}" class="block font-medium text-md text-gray-700">List's
                                label</label>
                            <input type="text" name="{{$form_elem}}" id="{{$form_elem}}"
                                   value="{{ old($lists_control->{$form_elem}, $lists_control->{$form_elem}) }}"
                                   class="form-input rounded-md shadow-sm mt-1 block w-full" placeholder="short"/>
                            <small id="{{$form_elem}}Help" class="block font-medium text-sm text-gray-500 ">It'll be
                                used as list identifier.</small>
                            @error($form_elem)
                            <p class="text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!--  KEY VALUE SECTION  -->
                        <div class="px-4 py-5 bg-white sm:p-6">
                            @php($form_elem = "key_value")
                            <label for="{{$form_elem}}" class="block font-medium text-md text-gray-700">List's
                                key value</label>
                            <input type="text" name="{{$form_elem}}" id="{{$form_elem}}"
                                   value="{{ old($lists_control->{$form_elem}, $lists_control->{$form_elem}) }}"
                                   class="form-input rounded-md shadow-sm mt-1 block w-full" placeholder="short"/>
                            <small id="{{$form_elem}}Help" class="block font-medium text-sm text-gray-500 ">It'll be
                                used as list id.</small>
                            @error($form_elem)
                            <p class="text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!--  DISPLAYED VALUE SECTION  -->
                        <div class="px-4 py-5 bg-white sm:p-6">
                            @php($form_elem = "displayed_value")
                            <label for="{{$form_elem}}" class="block font-medium text-md text-gray-700">Displayed
                                value</label>
                            <input type="text" name="{{$form_elem}}" id="{{$form_elem}}"
                                   value="{{ old($lists_control->{$form_elem}, $lists_control->{$form_elem}) }}"
                                   class="form-input rounded-md shadow-sm mt-1 block w-full" placeholder="short"/>
                            <small id="{{$form_elem}}Help" class="block font-medium text-sm text-gray-500 ">It's the
                                name of the attribute that has to be shown.</small>
                            @error($form_elem)
                            <p class="text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="flex items-center justify-end px-4 py-3 bg-gray-50 text-right sm:px-6">
                            <button
                                class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray disabled:opacity-25 transition ease-in-out duration-150">
                                Edit
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
