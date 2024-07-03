@php use App\Models\Place
@endphp
@section('title', __('place/show.view_place_details', ['place_name' => $place->name]))

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('place/show.view_place_details', ['place_name' => $place->name]) }}
        </h2>
    </x-slot>
    <div>
        <div class="max-w-6xl mx-auto py-10 sm:px-6 lg:px-8">
            <div class="block mb-8">
                @can("delete", $place)
                    <form action="{{route('place.destroy', $place->id)}}" method="POST"
                          class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                        @endcan

                        @can("viewAny", $place)
                            <a href="{{ route('place.index') }}"
                               class="bg-gray-200 hover:bg-gray-300 text-black font-bold py-2 px-4 rounded">{{ __('common.back') }}</a>
                        @endcan

                        @can("update", $place)
                            <a href="{{ route('place.edit', $place->id) }}"
                               class="bg-blue-200 hover:bg-blue-300 text-black font-bold py-2 px-4 rounded">{{ __('common.edit') }}</a>
                        @endcan

                        @can("delete", $place)
                            @method('DELETE')
                            @csrf
                            <button type="submit"
                                    class="flex-shrink-0 bg-red-200 hover:bg-red-300
                                    text-black font-bold py-2 px-4 rounded">
                                {{ __('common.delete') }}
                            </button>
                    </form>
                @endcan
            </div>

            <div class="flex flex-col">
                <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                    <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                        <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                            <table class="min-w-full divide-y divide-gray-200 w-full">
                                <caption class="sr-only">{{ __('place/show.place') }}</caption>
                                <tr class="border-b">
                                    <th scope="col"
                                        class="px-6 py-3 bg-gray-50 text-left text-xs font-medium
                                        text-gray-500 uppercase tracking-wider">
                                        {{ __('place/show.fields.name') }}
                                    </th>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900
                                    bg-white divide-y divide-gray-200">
                                        {{ $place->name }}
                                    </td>
                                </tr>
                                <tr class="border-b">
                                    <th scope="col"
                                        class="px-6 py-3 bg-gray-50 text-left text-xs font-medium
                                        text-gray-500 uppercase tracking-wider">
                                        {{ __('place/show.fields.coordinates') }}
                                    </th>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900
                                    bg-white divide-y divide-gray-200">
                                        <span>
                                        <div class="row">
                                            <div class="col-4">
                                                <span>
                                                    {{ __('place/show.fields.lat') }} : {{ json_decode($place->coordinates, true)['lat'] ?? ""}}
                                                </span>
                                            </div>
                                            <div class="col-4">
                                                <span>
                                                    {{ __('place/show.fields.lon') }} : {{ json_decode($place->coordinates, true)['long'] ?? ""}}
                                                </span>
                                            </div>
                                        </div>
                                    </span>
                                    </td>
                                </tr>
                                <tr class="border-b">
                                    <th scope="col"
                                        class="px-6 py-3 bg-gray-50 text-left text-xs font-medium
                                        text-gray-500 uppercase tracking-wider">
                                        {{ __('place/show.fields.area') }}
                                    </th>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900
                                    bg-white divide-y divide-gray-200">
                                        <span>
                                        <div class="row">
                                            <div class="col-4">
                                                @if($place->area)
                                                    <span>
                                                        {{ __('place/show.fields.number_of_polygons') }} : {{ count(json_decode($place->area, true)['polygons']) ?? ""}}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </span>
                                    </td>
                                </tr>
                                <tr class="border-b">
                                    <th scope="col"
                                        class="px-6 py-3 bg-gray-50 text-left text-xs font-medium
                                        text-gray-500 uppercase tracking-wider">
                                        {{ __('place/show.fields.description') }}
                                    </th>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900
                                    bg-white divide-y divide-gray-200">
                                        {{ $place->description }}
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="block mb-8 mt-3">
                <div class="-my-2 sm:-mx-6 lg:-mx-8">
                    <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                        <div style="height: 400px"
                             class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                            @include('map.leaflet', [
                                        'initialMarkers' => [
                                            ['lat' => json_decode($place->coordinates, true)['lat'], 'lng' => json_decode($place->coordinates, true)['long']]
                                        ],
                                        'initialArea' => [json_decode($place->area, true)['polygons']
                                    ]])
                        </div>
                    </div>
                </div>
            </div>

            <div class="block mt-8">
                <a href="{{ route('place.index') }}"
                   class="bg-gray-200 hover:bg-gray-300 text-black font-bold py-2 px-4 rounded">
                    {{ __('common.back') }}
                </a>
            </div>

        </div>
    </div>
</x-app-layout>
