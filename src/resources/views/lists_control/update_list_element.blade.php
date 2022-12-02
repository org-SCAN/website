@section('title',"Update an element to ".$listControl->title." list")
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Add to <b>{{ $listControl->title }}</b> list
        </h2>
    </x-slot>

    <div>
        <div class="max-w-6xl mx-auto py-10 sm:px-6 lg:px-8">
            <div class="block mb-8">
                <a href="{{ route('lists_control.show', $listControl) }}"
                   class="bg-gray-200 hover:bg-gray-300 text-black font-bold py-2 px-4 rounded">Back</a>
            </div>
            <div class="flex flex-col">
                <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                    <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                        <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-5 md:mt-0 md:col-span-2">
                <form method="post"
                      action="{{ route('lists_control.update_list_elem', [$listControl, $content->id]) }}">
                    @csrf
                    <div class="shadow overflow-hidden sm:rounded-md">
                        @foreach($list_fields as $listField)
                            <div class="px-4 py-5 bg-white sm:p-6">
                                @livewire('forms.form', [
                                    'form_elem' => $listField->field,
                                    'type' => $listField->dataType->html_type,
                                    'placeHolder' => '',
                                    'title' => $listField->field,
                                    'previous' => $content->{$listField->field},
                                    'associated_list' => $listField->list->first()->id ?? null,
                                ])
                            </div>
                        @endforeach
                    </div>
                    <div class="flex items-center justify-end px-4 py-3 bg-gray-50 text-right sm:px-6">
                        <button
                            class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray disabled:opacity-25 transition ease-in-out duration-150">
                            Update
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
