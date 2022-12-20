@php use App\Models\ListEventType; @endphp
@php use App\Models\ListCountry; @endphp
@section('title',"Edit ".$event->name)
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Edit an {{ $event->name }}
        </h2>
    </x-slot>
    <div>
        <div class="max-w-4xl mx-auto py-10 sm:px-6 lg:px-8">
            <div class="block mb-8">
                <a href="{{ route('event.index') }}"
                   class="bg-gray-200 hover:bg-gray-300 text-black font-bold py-2 px-4 rounded">Back</a>
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
                                'title' => "Event's name",
                                'hint' => "The name of the event.",
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
                                'title' => 'Event\'s type',
                                'associated_list' => $list,
                                'previous' => old($form_elem, $event->{$form_elem}),
                                'placeHolder' => '-- Select the event type --',
                                'hint' => "The event type.",
                            ])
                        </div>


                        <!--  Country SECTION  -->
                        <div class="px-4 py-5 bg-white sm:p-6">
                            @php($form_elem = "country_id")
                            @php($list = ListCountry::list())
                            @livewire('forms.form', [
                                'form_elem' => $form_elem,
                                'title' => "Event's Country",
                                'type' => 'select-dropdown',
                                'hint' => "The country associated to the event.",
                                "placeHolder" => "-- Select the event country --",
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
                                'title' => "Event's precise location",
                                'hint' => "The precise location of the event.",
                                'previous' => $event->{$form_elem}])
                        </div>

                        <!--  start date SECTION  -->
                        <div class="px-4 py-5 bg-white sm:p-6">
                            @php($form_elem = "start_date")
                            @livewire("forms.form", [
                                'form_elem' => $form_elem,
                                'type' => "date",
                                'title' => "Event's start date",
                                'hint' => "The start date.",
                                'previous' => $event->{$form_elem}])
                        </div>

                        <!--  stop date SECTION  -->
                        <div class="px-4 py-5 bg-white sm:p-6">
                            @php($form_elem = "stop_date")
                            @livewire("forms.form", [
                                'form_elem' => $form_elem,
                                'type' => "date",
                                'title' => "Event's stop date",
                                'hint' => "The stop date.",
                                'previous' => $event->{$form_elem}])
                        </div>

                        <!--  coordinates SECTION  -->
                        <div class="px-4 py-5 bg-white sm:p-6">
                            @php($form_elem = "coordinates")
                            @livewire("forms.form", [
                                'form_elem' => $form_elem,
                                'type' => "coordinates",
                                'title' => "The coordinates of the event",
                                'hint' => "A latitude and longitude in decimal degree.",
                                'previous' => $event->{$form_elem},
                                'dataType' => \App\Models\ListDataType::where('name', 'Coordinates')->first()
                                ])
                        </div>

                        <!--  description SECTION  -->
                        <div class="px-4 py-5 bg-white sm:p-6">
                            @php($form_elem = "description")
                            @livewire("forms.form", [
                                'form_elem' => $form_elem,
                                'type' => "textarea",
                                'title' => "Event's description",
                                'previous' => $event->{$form_elem}])
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
