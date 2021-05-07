<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Edit : <b>{{$refugee->full_name}}</b>
        </h2>
    </x-slot>

    <div>
        <div class="max-w-4xl mx-auto py-10 sm:px-6 lg:px-8">
            <div class="block mb-8">
                <a href="{{URL::previous() }}" class="bg-gray-200 hover:bg-gray-300 text-black font-bold py-2 px-4 rounded">Back</a>
            </div>
            <div class="mt-5 md:mt-0 md:col-span-2">
                <form method="post" action="{{ route('manage_refugees.update', $refugee) }}">
                    @csrf
                    @method('PUT')
                    <div class="shadow overflow-hidden sm:rounded-md">
                        @foreach($fields as $field)

                            <div class="px-4 py-5 bg-white sm:p-6">
                                <label for="{{$field->label}}"
                                       class="block font-medium text-sm text-gray-700">{{$field->title}}</label>
                                @if($field->linked_list != "")
                                    @php
                                        $list=$field->getLinkedListContent();
                                        $get_id = "get".ucfirst($field->label)."Id";
                                    @endphp
                                    @livewire("select-dropdown", ['label' => $field->label, 'placeholder' => "-- Select
                                    your ".$field->title." --", 'datas' => $list, "selected_value" =>
                                    $refugee->$get_id()])
                                    @stack('scripts')

                                @elseif($field->html_data_type == "textarea")
                                    <textarea name="{{$field->label}}" id="{{$field->label}}"
                                              class="form-input rounded-md shadow-sm mt-1 block w-full"
                                              placeholder="{{$field->placeholder ?? ''}}">{{ old($field->label, $refugee->{$field->label}) }}</textarea>
                                @else
                                    <input type="{{$field->html_data_type}}" name="{{$field->label}}"
                                           id="{{$field->label}}"
                                           class="form-input rounded-md shadow-sm mt-1 block w-full"
                                           value="{{ old($field->label, $refugee->{$field->label}) }}"/>
                                @endif
                                @error($field->label)
                                <p class="text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        @endforeach
                        <div class="flex items-center justify-end px-4 py-3 bg-gray-50 text-right sm:px-6">
                            <button class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray disabled:opacity-25 transition ease-in-out duration-150">
                                Edit
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
