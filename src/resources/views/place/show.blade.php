@php use App\Models\Place @endphp

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('place/index.place') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="block mb-8">
                <a href="{{ route('place.index') }}"
                   class="bg-gray-200 hover:bg-gray-300 text-black font-bold py-2 px-4 rounded">
                    Back
                </a>
            </div>

            <h1>Showing {{ $place->name }}</h1>

            <div class="jumbotron text-center">
                <p>
                    <strong>ID:</strong> {{ $place->id }}<br>
                    <strong>Name:</strong> {{ $place->name }}<br>
                    <strong>Latitude:</strong> {{ $place->lat }}<br>
                    <strong>Longitude:</strong> {{ $place->lon }}<br>
                    <strong>Description:</strong> {{ $place->description }}
                </p>
            </div>

            <div class="block mb-8">
                <div class="-my-2 sm:-mx-6 lg:-mx-8">
                    <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                        <div style="height: 400px"
                             class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                            @map([
                            'lat' => $place->lat,
                            'lng' => $place->lon,
                            'zoom' => 10,
                            'markers' => [
                            ['lat' => $place->lat, 'lng' => $place->lon],
                            ]
                            ])
                            @mapscripts
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
