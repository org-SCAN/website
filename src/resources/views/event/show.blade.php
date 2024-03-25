@section('title', __('event/show.view_event_details', ['event_name' => $event->name]))
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('event/show.event_details', ['event_name' => $event->name]) }}
        </h2>
    </x-slot>

    <div>
        <div class="max-w-6xl mx-auto py-10 sm:px-6 lg:px-8">
            <div class="block mb-8">
                @can("delete", $event)
                    <form action="{{route('event.destroy', $event->id)}}" method="POST"
                          class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                        @endcan

                        @can("viewAny", $event)
                            <a href="{{ route('event.index') }}"
                               class="bg-gray-200 hover:bg-gray-300 text-black font-bold py-2 px-4 rounded">{{ __('common.back') }}</a>
                        @endcan

                        @can("update", $event)
                            <a href="{{ route('event.edit', $event->id) }}"
                               class="bg-blue-200 hover:bg-blue-300 text-black font-bold py-2 px-4 rounded">{{ __('common.edit') }}</a>
                        @endcan


                        @can("delete", $event)
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
                                <caption class="sr-only">{{ __('event/show.event_detail') }}</caption>
                                <tr class="border-b">
                                    <th scope="col"
                                        class="px-6 py-3 bg-gray-50 text-left text-xs font-medium
                                        text-gray-500 uppercase tracking-wider">
                                        {{ __('event/show.name') }}
                                    </th>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900
                                    bg-white divide-y divide-gray-200">
                                        {{ $event->name }}
                                    </td>
                                </tr>
                                <tr class="border-b">
                                    <th scope="col"
                                        class="px-6 py-3 bg-gray-50 text-left text-xs font-medium
                                        text-gray-500 uppercase tracking-wider">
                                        {{ __('event/show.event_type') }}
                                    </th>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900
                                    bg-white divide-y divide-gray-200">
                                        {{ $event->type->displayed_value_content }}
                                    </td>
                                </tr>
                                <tr class="border-b">
                                    <th scope="col"
                                        class="px-6 py-3 bg-gray-50 text-left text-xs font-medium
                                        text-gray-500 uppercase tracking-wider">
                                        {{ __('event/show.country') }}
                                    </th>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900
                                    bg-white divide-y divide-gray-200">
                                        {{ $event->country->displayed_value_content ?? "" }}
                                    </td>
                                </tr>
                                <tr class="border-b">
                                    <th scope="col"
                                        class="px-6 py-3 bg-gray-50 text-left text-xs font-medium
                                        text-gray-500 uppercase tracking-wider">
                                        {{ __('event/show.location_details') }}
                                    </th>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900
                                    bg-white divide-y divide-gray-200">
                                        {{ $event->location_details }}
                                    </td>
                                </tr>
                                <tr class="border-b">
                                    <th scope="col"
                                        class="px-6 py-3 bg-gray-50 text-left text-xs font-medium
                                        text-gray-500 uppercase tracking-wider">
                                        {{ __('event/show.start_date') }}
                                    </th>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900
                                    bg-white divide-y divide-gray-200">
                                        {{ $event->start_date }}
                                    </td>
                                </tr>
                                <tr class="border-b">
                                    <th scope="col"
                                        class="px-6 py-3 bg-gray-50 text-left text-xs font-medium
                                        text-gray-500 uppercase tracking-wider">
                                        {{ __('event/show.stop_date') }}
                                    </th>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900
                                    bg-white divide-y divide-gray-200">
                                        {{ $event->stop_date }}
                                    </td>
                                </tr>
                                <tr class="border-b">
                                    <th scope="col"
                                        class="px-6 py-3 bg-gray-50 text-left text-xs font-medium
                                        text-gray-500 uppercase tracking-wider">
                                        {{ __('event/show.coordinates') }}
                                    </th>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900
                                    bg-white divide-y divide-gray-200">
                                    @if($event->coordinates)
                                    <span>
                                        <div class="row">
                                            <div class="col-4">
                                                <span>
                                                    {{ __('event/show.lat') }} : {{ json_decode($event->coordinates, true)['lat'] ?? ""}}
                                                </span>
                                            </div>
                                            <div class="col-4">
                                                <span>
                                                    {{ __('event/show.long') }} : {{ json_decode($event->coordinates, true)['long'] ?? ""}}
                                                </span>
                                            </div>
                                        </div>
                                    </span>
                                    @endif
                                    </td>
                                </tr>
                                <tr class="border-b">
                                    <th scope="col"
                                        class="px-6 py-3 bg-gray-50 text-left text-xs font-medium
                                        text-gray-500 uppercase tracking-wider">
                                        {{ __('event/show.description') }}
                                    </th>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900
                                    bg-white divide-y divide-gray-200">
                                        {{ $event->description }}
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            @if($event->coordinates)
            <div class="block mb-8 mt-3">
                <div class="-my-2 sm:-mx-6 lg:-mx-8">
                    <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                        <div style="height: 400px" class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                            @map([
                                'lat' => json_decode($event->coordinates, true)['lat'],
                                'lng' => json_decode($event->coordinates, true)['long'],
                                'zoom' => 2,
                                'markers' => [
                                    [
                                        'lat' => json_decode($event->coordinates, true)['lat'],
                                        'lng' => json_decode($event->coordinates, true)['long'],
                                        'title' => $event->name,
                                    ],
                                ]
                            ])
                            @mapscripts
                        </div>
                    </div>
                </div>
            </div>
            @endif
            <div class="block mt-8">
                <a href="{{ route('event.index') }}"
                   class="bg-gray-200 hover:bg-gray-300 text-black font-bold py-2 px-4 rounded">
                    {{ __('common.back') }}
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
