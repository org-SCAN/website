@php use App\Models\ListsourceType; @endphp
@section('title',"Edit ".$source->name)
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Add an source
        </h2>
    </x-slot>
    <div>
        <div class="max-w-4xl mx-auto py-10 sm:px-6 lg:px-8">
            <div class="block mb-8">
                <a href="{{ route('source.index')  }}"
                   class="bg-gray-200 hover:bg-gray-300 text-black font-bold py-2 px-4 rounded">Back</a>
            </div>
            <div class="mt-5 md:mt-0 md:col-span-2">
                <form method="post" action="{{ route('source.update', $source) }}">
                    @csrf
                    @method("put")
                    <div class="shadow overflow-hidden sm:rounded-md">

                        <!--  Name SECTION  -->
                        <div class="px-4 py-5 bg-white sm:p-6">
                            @php($form_elem = "name")
                            <label for="{{$form_elem}}" class="block font-medium text-md text-gray-700">Source's
                                name</label>
                            <input value="{{ old($form_elem, $source->{$form_elem}) }}" type="text"
                                   name="{{$form_elem}}"
                                   id="{{$form_elem}}"
                                   class="form-input rounded-md shadow-sm mt-1 block w-full"/>
                            <small id="{{$form_elem}}Help" class="block font-medium text-sm text-gray-500">The name of
                                the source</small>
                            @error($form_elem)
                            <p class="text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!--  Source Type SECTION  -->
                        <div class="px-4 py-5 bg-white sm:p-6">
                            @php($form_elem = "source_type_id")
                            @php($list = ListSourceType::list())
                            <label for="{{$form_elem}}" class="block font-medium text-md text-gray-700">Source's
                                Type</label>
                            @livewire("select-dropdown", ['label' => $form_elem, 'placeholder' => "--
                            Select the source type --", 'datas' => $list, 'selected_value' =>
                            old($form_elem, $source->{$form_elem})])

                            <small id="{{$form_elem}}Help" class="block font-medium text-sm text-gray-500">The type of
                                the source</small>
                            @error($form_elem)
                            <p class="text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>


                        <!--  description SECTION  -->
                        <div class="px-4 py-5 bg-white sm:p-6">
                            @php($form_elem = "reference")
                            Source's reference
                            <textarea name="{{ $form_elem }}" id="{{ $form_elem }}"
                                      class="form-input rounded-md shadow-sm mt-1 block w-full"
                                      placeholder="{{ $form_elem ?? '' }}">{{ old($form_elem, $source->{$form_elem}) }}</textarea>
                            @error($form_elem)
                            <p class="text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center justify-end px-4 py-3 bg-gray-50 text-right sm:px-6">
                            <button
                                    class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray disabled:opacity-25 transition ease-in-out duration-150">
                                Save
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
