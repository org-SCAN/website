@section('title', __("sources/show.title", ["source" => $source->name]))
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            <strong>{{ $source->name }}</strong> {{ __('sources/show.details') }}
        </h2>
        </h2>
    </x-slot>

    <div>
        <div class="max-w-6xl mx-auto py-10 sm:px-6 lg:px-8">
            <div class="block mb-8">
                @can("delete", $source)
                    <form action="{{route('source.destroy', $source->id)}}" method="POST"
                          class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                        @endcan

                        @can("viewAny", $source)
                            <a href="{{ route('source.index') }}"
                               class="bg-gray-200 hover:bg-gray-300 text-black font-bold py-2 px-4 rounded">{{ __("common.back") }}</a>
                        @endcan

                        @can("update", $source)
                            <a href="{{ route('source.edit', $source->id) }}"
                               class="bg-blue-200 hover:bg-blue-300 text-black font-bold py-2 px-4 rounded">{{ __("common.edit") }}</a>
                        @endcan


                        @can("delete", $source)
                            @method('DELETE')
                            @csrf
                            <button type="submit"
                                    class="flex-shrink-0 bg-red-200 hover:bg-red-300 text-black
                                    font-bold py-2 px-4 rounded">
                                {{ __("common.delete") }}
                            </button>
                    </form>
                @endcan
            </div>
            <div class="flex flex-col">
                <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                    <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                        <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                            <table class="min-w-full divide-y divide-gray-200 w-full">
                                <caption class="sr-only">Source detail</caption>
                                <tr class="border-b">
                                    <th scope="col"
                                        class="px-6 py-3 bg-gray-50 text-left text-xs
                                        font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('sources/show.name') }}
                                    </th>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm
                                    text-gray-900 bg-white divide-y divide-gray-200">
                                        {{ $source->name }}
                                    </td>
                                </tr>
                                <tr class="border-b">
                                    <th scope="col"
                                        class="px-6 py-3 bg-gray-50 text-left text-xs
                                        font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('sources/show.source_type') }}
                                    </th>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm
                                    text-gray-900 bg-white divide-y divide-gray-200">
                                        {{ $source->type->displayed_value_content }}
                                    </td>
                                </tr>
                                <tr class="border-b">
                                    <th scope="col"
                                        class="px-6 py-3 bg-gray-50 text-left text-xs
                                        font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('sources/show.trust') }}
                                    </th>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm
                                    text-gray-900 bg-white divide-y divide-gray-200">
                                        {{ $source->trust }}
                                    </td>
                                </tr>
                                <tr class="border-b">
                                    <th scope="col"
                                        class="px-6 py-3 bg-gray-50 text-left text-xs
                                        font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('sources/show.reference') }}
                                    </th>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm
                                    text-gray-900 bg-white divide-y divide-gray-200">
                                        {{ $source->reference }}
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
