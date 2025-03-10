@php use App\Models\ListEventType; @endphp
@php use App\Models\ListCountry; @endphp
@section('title', __('event/edit.edit_event', ['event_name' => $event->name]))
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('event/edit.edit_event', ['event_name' => $event->name]) }}
        </h2>
    </x-slot>
    <div>
        <div class="max-w-4xl mx-auto py-10 sm:px-6 lg:px-8">
            <div class="block mb-8">
                <a href="{{ route('event.index') }}"
                   class="bg-gray-200 hover:bg-gray-300 text-black font-bold py-2 px-4 rounded">
                    {{ __('common.back') }}
                </a>
            </div>
            <div class="mt-5 md:mt-0 md:col-span-2">
                <form method="post" action="{{ route('event.update', $event) }}">
                    @csrf
                    @method("put")
                    <div class="shadow overflow-hidden sm:rounded-md">

                        <!--  Name SECTION  -->
                        <div class="px-4 py-5 bg-white sm:p-6">
                            @php($form_elem = "name")
                            @livewire("forms.form", [
                                'form_elem' => $form_elem,
                                'type' => "text",
                                 'title' => __("event/create.event_name"),
                                'hint' => __("event/create.event_name_hint"),
                                'previous' => $event->{$form_elem}
                            ])
                        </div>

                        <!--  Event Type SECTION  -->
                        <div class="px-4 py-5 bg-white sm:p-6">
                            @php($form_elem = "event_type_id")
                            @php($list = ListEventType::list())
                            @livewire('forms.form', [
                                'form_elem' => $form_elem,
                                'type' => 'select-dropdown',
                                'title' => __("event/create.event_type"),
                                'associated_list' => $list,
                                'previous' => old($form_elem, $event->{$form_elem}),
                                'placeHolder' => __("event/create.select_event_type"),
                            ])
                        </div>


                        <!--  Country SECTION  -->
                        <div class="px-4 py-5 bg-white sm:p-6">
                            @php($form_elem = "country_id")
                            @php($list = ListCountry::list())
                            @livewire('forms.form', [
                                'form_elem' => $form_elem,
                                'title' => __("event/create.event_country"),
                                'type' => 'select-dropdown',
                                'hint' => __("event/create.event_country_hint"),
                                "placeHolder" => __("event/create.select_event_country"),
                                "associated_list" => $list,
                                'previous' => old($form_elem, $event->{$form_elem}),
                            ])
                        </div>

                        <!--  Precise location SECTION  -->
                        <div class="px-4 py-5 bg-white sm:p-6">
                            @php($form_elem = "location_details")
                            @livewire("forms.form", [
                                'form_elem' => $form_elem,
                                'type' => "text",
                                'title' => __('event/create.location_details'),
                                'hint' => __('event/create.location_details_hint'),
                                'previous' => $event->{$form_elem}])
                        </div>

                        <!--  start date SECTION  -->
                        <div class="px-4 py-5 bg-white sm:p-6">
                            @php($form_elem = "start_date")
                            @livewire("forms.form", [
                                'form_elem' => $form_elem,
                                'type' => "date",
                                'title' => __('event/create.start_date'),
                                'hint' => __('event/create.start_date_hint'),
                                'previous' => $event->{$form_elem}])
                        </div>

                        <!--  stop date SECTION  -->
                        <div class="px-4 py-5 bg-white sm:p-6">
                            @php($form_elem = "stop_date")
                            @livewire("forms.form", [
                                'form_elem' => $form_elem,
                                'type' => "date",
                                'title' => __('event/create.stop_date'),
                                'hint' => __('event/create.stop_date_hint'),
                                'previous' => $event->{$form_elem}])
                        </div>

                        <!--  coordinates SECTION  -->
                        <div class="px-4 py-5 bg-white sm:p-6">
                            @php($form_elem = "coordinates")
                            @livewire("forms.form", [
                                'form_elem' => $form_elem,
                                'type' => "coordinates",
                                'title' => __('event/create.coordinates'),
                                'hint' => __('event/create.coordinates_hint'),
                                'previous' => $event->{$form_elem},
                                'dataType' => \App\Models\ListDataType::where('name', 'Coordinates')->first()
                                ])
                        </div>

                        <!--  area SECTION  -->
                        <div class="px-4 py-5 bg-white sm:p-6">
                            @php($form_elem = "area")
                            @livewire("forms.form", [
                                'form_elem' => $form_elem,
                                'type' => "area",
                                'title' => __('event/create.area'),
                                'hint' => __('event/create.area_hint'),
                                'previous' => $event->{$form_elem},
                                'dataType' => \App\Models\ListDataType::where('name', 'Area')->first()
                                ])
                        </div>

                        <!--  description SECTION  -->
                        <div class="px-4 py-5 bg-white sm:p-6">
                            @php($form_elem = "description")
                            @livewire("forms.form", [
                                'form_elem' => $form_elem,
                                'type' => "textarea",
                                'title' => __('event/create.description'),
                                'previous' => $event->{$form_elem}])
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
