@php use App\Models\Places @endphp

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('places/index.places') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="block mb-8">
                <a href="{{ route('places.index') }}"
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

            <!-- TODO: Add a map here -->

        </div>
    </div>
</x-app-layout>
