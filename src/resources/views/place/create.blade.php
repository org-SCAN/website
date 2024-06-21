@php use App\Models\Place @endphp
@section('title', __('place/create.title'))

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('place/index.place') }}
        </h2>
    </x-slot>
    <div>
        <div class="max-w-4xl mx-auto py-10 sm:px-6 lg:px-8">
            <div class="block mb-8">
                <a href="{{ route('place.index') }}"
                   class="bg-gray-200 hover:bg-gray-300 text-black font-bold py-2 px-4 rounded">
                    {{__('common.back')}}
                </a>
            </div>
            <div class="mt-5 md:mt-0 md:col-span-2">
                <form action="{{ route('place.store') }}" method="post"
                      enctype="multipart/form-data">
                    @csrf
                    <div class="shadow overflow-hidden sm:rounded-md">

                        <div class="px-4 py-5 bg-white sm:p-6">
                            @php($form_elem = "name")
                            @livewire("forms.form", [
                                'form_elem' => $form_elem,
                                'type' => "text",
                                'title' => __('place/create.fields.name'),
                                'hint' => __('place/create.placeholders.name')
                                ])
                        </div>

                        <div class="px-4 py-5 bg-white sm:p-6">
                            @php($form_elem = "coordinates")
                            @livewire("forms.form", [
                                'form_elem' => $form_elem,
                                'type' => "coordinates",
                                'title' => __('place/create.fields.coordinates'),
                                'hint' => __('place/create.placeholders.coordinates')
                                ])
                        </div>

                        <div class="px-4 py-5 bg-white sm:p-6">
                            @php($form_elem = "area")
                            @livewire("forms.form", [
                                'form_elem' => $form_elem,
                                'type' => "area",
                                'title' => __('place/create.fields.area'),
                                'hint' => __('place/create.placeholders.area')
                                ])
                        </div>
                        <div class="flex items-center justify-end px-4 py-3 bg-gray-50 text-right sm:px-6">
                            <button type="submit"
                                    class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray disabled:opacity-25 transition ease-in-out duration-150">
                                {{ __('place/create.fields.add_polygon') }}
                            </button>
                        </div>

                        <div class="px-4 py-5 bg-white sm:p-6">
                            @php($form_elem = "description")
                            @livewire("forms.form", [
                                'form_elem' => $form_elem,
                                'type' => "text",
                                'title' => __('place/create.fields.description'),
                                'hint' => __('place/create.placeholders.description')
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
