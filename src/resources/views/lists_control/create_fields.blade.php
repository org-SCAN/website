@section('title',"Create the list's fields")
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Create the {{ $listControl->title }} list's fields
        </h2>
    </x-slot>
    <div>
        <div class="max-w-4xl mx-auto py-10 sm:px-6 lg:px-8">
            <div class="block mb-8">
                <p>
                    In this section, you may define all the fields that are required to describe your list.
                    As an example you can create 'name', 'inhabitants' if you want to create a list related to Cities.
                </p>
            </div>

            <div class="mt-5 md:mt-0 md:col-span-2">
                <form method="post" action="{{ route('lists_control.store_fields', $listControl) }}">
                    @csrf
                    <div class="shadow overflow-hidden sm:rounded-md">

                        <!--  Field 0 SECTION  -->
                        <div class="px-4 py-5 bg-white sm:p-6">
                            @php($form_elem = "fields[0]")
                            <label for="{{$form_elem}}" class="block font-medium text-md text-gray-700">Field's title</label>
                            <input type="text" name="{{$form_elem}}" id="{{$form_elem}}" class="form-input rounded-md shadow-sm mt-1 block w-full" placeholder="e.g. name" />
                            <small id="{{$form_elem}}Help" class="block font-medium text-sm text-gray-500">The name of the field</small>
                            @error($form_elem)
                            <p class="text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!--  Field 1 SECTION  -->
                        <div class="px-4 py-5 bg-white sm:p-6">
                            @php($form_elem = "fields[1]")
                            <label for="{{$form_elem}}" class="block font-medium text-md text-gray-700">Field's title</label>
                            <input type="text" name="{{$form_elem}}" id="{{$form_elem}}" class="form-input rounded-md shadow-sm mt-1 block w-full" placeholder="e.g. description" />
                            <small id="{{$form_elem}}Help" class="block font-medium text-sm text-gray-500">The name of the field</small>
                            @error($form_elem)
                            <p class="text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!--  Field 2 SECTION  -->
                        <div class="px-4 py-5 bg-white sm:p-6">
                            @php($form_elem = "fields[2]")
                            <label for="{{$form_elem}}" class="block font-medium text-md text-gray-700">Field's title</label>
                            <input type="text" name="{{$form_elem}}" id="{{$form_elem}}" class="form-input rounded-md shadow-sm mt-1 block w-full" placeholder="e.g. size" />
                            <small id="{{$form_elem}}Help" class="block font-medium text-sm text-gray-500">The name of the field</small>
                            @error($form_elem)
                            <p class="text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!--  Field 3 SECTION  -->
                        <div class="px-4 py-5 bg-white sm:p-6">
                            @php($form_elem = "fields[3]")
                            <label for="{{$form_elem}}" class="block font-medium text-md text-gray-700">List's title</label>
                            <input type="text" name="{{$form_elem}}" id="{{$form_elem}}" class="form-input rounded-md shadow-sm mt-1 block w-full" placeholder="e.g. color" />
                            <small id="{{$form_elem}}Help" class="block font-medium text-sm text-gray-500">The name of the field</small>
                            @error($form_elem)
                            <p class="text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>


                        <div class="flex items-center justify-end px-4 py-3 bg-gray-50 text-right sm:px-6">
                            <button class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray disabled:opacity-25 transition ease-in-out duration-150">
                                Next
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
