@php use App\Models\ListSourceType; @endphp
@section('title', __("sources/create.title"))
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('sources/create.section_title') }}
        </h2>
    </x-slot>
    <div>
        <div class="max-w-4xl mx-auto py-10 sm:px-6 lg:px-8">
            <div class="block mb-8">
                <a href="{{ route('source.index')  }}"
                   class="bg-gray-200 hover:bg-gray-300 text-black font-bold py-2 px-4 rounded">{{ __('common.back') }}</a>
            </div>
            <div class="mt-5 md:mt-0 md:col-span-2">
                <form method="post" action="{{ route('source.store') }}">
                    @csrf
                    <div class="shadow overflow-hidden sm:rounded-md">

                        <!--  Name SECTION  -->
                        <div class="px-4 py-3 bg-white sm:p-6">
                            @php($form_elem = "name")
                            @livewire("forms.form", [
                                'form_elem' => $form_elem,
                                'type' => "text",
                                'title' => __("sources/create.field_name.title"),
                                'placeHolder' => __("sources/create.field_name.placeholder"),
                                'hint' => __("sources/create.field_name.hint"),
                                'previous' => old($form_elem),
                            ])
                        </div>
                        <!--  Source Type SECTION  -->
                        <div class="px-4 py-3 bg-white sm:p-6">
                            @php($form_elem = "source_type_id")
                            @livewire("forms.form", [
                                'form_elem' => $form_elem,
                                'type' => "select-dropdown",
                                'associated_list' => ListSourceType::list(),
                                'title' => __("sources/create.field_type.title"),
                                'placeHolder' => __("sources/create.field_type.placeholder"),
                                'hint' => __("sources/create.field_type.hint"),
                                'previous' => old($form_elem, $form_elem),
                            ])
                        </div>

                        <!--  description SECTION  -->
                        <div class="px-4 py-3 bg-white sm:p-6">
                            @php($form_elem = "reference")
                            @livewire("forms.form", [
                               'form_elem' => $form_elem,
                               'type' => "textarea",
                               'title' => __("sources/create.field_reference.title"),
                               'placeHolder' => __("sources/create.field_reference.placeholder"),
                               'hint' => __("sources/create.field_reference.hint"),
                               'previous' => old($form_elem),
                           ])
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
</x-app-layout>
