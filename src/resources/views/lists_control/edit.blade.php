@section('title', __("lists_control/edit.title", ["list_title" => $lists_control->title]))
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {!! __("lists_control/edit.section_title", ["list_title" => $lists_control->title]) !!}
        </h2>
    </x-slot>

    <div>
        <div class="max-w-4xl mx-auto py-10 sm:px-6 lg:px-8">
            <div class="block mb-8">
                <a href="{{ route('lists_control.index')  }}" class="bg-gray-200 hover:bg-gray-300 text-black font-bold py-2 px-4 rounded">
                    {{ __("lists_control/edit.back") }}
                </a>
            </div>
            <div class="mt-5 md:mt-0 md:col-span-2">
                <form method="post" action="{{ route('lists_control.update', $lists_control) }}">
                    @csrf
                    @method('PUT')
                    <div class="shadow overflow-hidden sm:rounded-md">
                        <!--  TITLE SECTION  -->
                        <div class="px-4 py-5 bg-white sm:p-6">
                            @php($form_elem = "title")
                            @livewire('forms.form', [
                                'form_elem' => $form_elem,
                                'type' => 'text',
                                'label' => __("lists_control/edit.list_title_label"),
                                'placeholder' => __("lists_control/edit.list_title_placeholder"),
                                'hint' => __("lists_control/edit.list_title_hint"),
                                'previous' => old($lists_control->{$form_elem}, $lists_control->{$form_elem}),
                            ])
                        </div>
                        <div class="flex items-center justify-end px-4 py-3 bg-gray-50 text-right sm:px-6">
                            <button
                                class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray disabled:opacity-25 transition ease-in-out duration-150">
                                {{ __("lists_control/edit.save") }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
