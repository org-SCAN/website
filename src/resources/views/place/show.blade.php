@php use App\Models\Place; @endphp
@php use App\Models\Link; @endphp
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
            @can("viewMenu", Link::class)
                <div class="block mt-8 mb-8 flex flex-col">
                    <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                        <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                            <h2 class="font-semibold text-xl text-gray-800 leading-tight mb-8">
                                {{ __('place/show.relation') }}
                            </h2>
                            <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                                <div class="block mb-8 mt-4">
                                    <a href="{{ route('cytoscape.index', ["from"=>$place->id]) }}"
                                       class="bg-gray-200 hover:bg-gray-300 hover:text-black text-black
                                       font-bold py-2 px-4 rounded m-3">
                                        {{ __('place/show.view_relations_in_graph') }}
                                    </a>
                                </div>
                                <table class="min-w-full divide-y divide-gray-200 w-full">
                                    <caption class="sr-only">{{ __('place/show.item_relation') }}</caption>
                                    <thead>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium
                                        text-gray-800 uppercase tracking-wider">
                                        {{ __('place/show.from') }}
                                    </th>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium
                                        text-gray-800 uppercase tracking-wider">
                                        {{ __('place/show.relation') }}
                                    </th>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium
                                        text-gray-800 uppercase tracking-wider">
                                        {{ __('place/show.to') }}
                                    </th>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium
                                        text-gray-800 uppercase tracking-wider"></th>
                                    </thead>
                                    @if($place->relations)
                                        @foreach($place->relations as $sided_relations)
                                            @foreach($sided_relations as $link)
                                                <tr class="border-b">
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900
                                                    bg-white divide-y divide-gray-200">
                                                        <a href="{{route("place.show", $link->pivot->from)}}">
                                                            @if($link->pivot->refugeeFrom)
                                                                {{$link->pivot->refugeeFrom->best_descriptive_value }}
                                                            @elseif($link->pivot->eventFrom)
                                                                {{$link->pivot->eventFrom->name}}
                                                            @elseif($link->pivot->placeFrom)
                                                                {{$link->pivot->placeFrom->name}}
                                                            @endif
                                                        </a>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm
                                                    text-gray-900 bg-white divide-y divide-gray-200">
                                                        {{ $link->name }}
                                                        <small>{{$link->detail}}</small>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm
                                                    text-gray-900 bg-white divide-y divide-gray-200">
                                                        <a href="{{route("place.show", $link->pivot->to)}}">
                                                            @if($link->pivot->refugeeTo)
                                                                {{$link->pivot->refugeeTo->best_descriptive_value }}
                                                            @elseif($link->pivot->eventTo)
                                                                {{$link->pivot->eventTo->name}}
                                                            @elseif($link->pivot->placeTo)
                                                                {{$link->pivot->placeTo->name}}
                                                            @endif
                                                        </a>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm
                                                    text-gray-900 bg-white divide-y divide-gray-200">
                                                        <a href="{{route("links.edit", $link->pivot->id)}}">
                                                            {{ __('place/show.edit') }}
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endforeach
                                    @endif
                                    @if(Link::$quickAdd)
                                        @can('create', Link::class)
                                            <tr class="border-b mb-4">
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                            <tr class="border-b mt-4">
                                                <td class="px-6 py-4 whitespace-nowrap text-sm
                                                    text-gray-900 bg-gray-100 divide-y divide-gray-300">
                                                    <a href="{{ route("place.show", $place->id) }}">
                                                        {{ $place->name }}
                                                    </a>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900
                                                    bg-gray-100 divide-y divide-gray-300"></td>

                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900
                                                    bg-gray-100 divide-y divide-gray-300">
                                                    <a href="{{ route("links.create", ["origin" => 'from', 'place' => $place]) }}"
                                                       class="bg-green-200 hover:bg-green-300
                                                           text-black font-bold py-2 px-4 rounded">
                                                        {{ __('place/show.add_to') }}
                                                    </a>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900
                                                    bg-gray-100 divide-y divide-gray-300"></td>
                                            </tr>
                                            <tr class="border-b">
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900
                                                    bg-gray-100 divide-y divide-gray-300">
                                                    <a href="{{ route("links.create", ["origin" => 'to', 'place' => $place]) }}"
                                                       class="bg-green-200 hover:bg-green-300 text-black
                                                           font-bold py-2 px-4 rounded">
                                                        {{ __('place/show.add_from') }}
                                                    </a>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900
                                                    bg-gray-100 divide-y divide-gray-300"></td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900
                                                    bg-gray-100 divide-y divide-gray-300">
                                                    <a href="{{ route("place.show", $place->id) }}">
                                                        {{ $place->name }}
                                                    </a>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900
                                                    bg-gray-100 divide-y divide-gray-300"></td>
                                            </tr>
                                        @endcan
                                    @endif
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            @endcan
            <div class="block mt-8">
                <a href="{{ route('place.index') }}"
                   class="bg-gray-200 hover:bg-gray-300 text-black font-bold py-2 px-4 rounded">
                    {{ __('common.back') }}
                </a>
            </div>

        </div>
    </div>
</x-app-layout>
