@section('title',"Show ".$api_log->id." details")
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Detail of push:
            <stong> {{$api_log->id}}</stong>
        </h2>
    </x-slot>

    <div>
        <div class="max-w-6xl mx-auto py-10 sm:px-6 lg:px-8">
            <div class="block mb-8">
                <a href="{{ route('api_logs.index') }}"
                   class="bg-gray-200 hover:bg-gray-300 text-black font-bold py-2 px-4 rounded">Back to list</a>
            </div>
            <div class="block mt-8 flex flex-col">
                <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                    <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                        <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                            <table class="min-w-full divide-y divide-gray-200 w-full">
                                <caption class="sr-only">Api Log detail</caption>
                                <tr class="border-b">
                                    <th scope="col"
                                        class="px-6 py-3 bg-gray-50 text-left text-xs font-medium
                                        text-gray-500 uppercase tracking-wider">
                                        Date
                                    </th>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900
                                    bg-white divide-y divide-gray-200">
                                        {{ $api_log->created_at }}
                                    </td>
                                </tr>

                                <tr class="border-b">
                                    <th scope="col"
                                        class="px-6 py-3 bg-gray-50 text-left text-xs font-medium
                                        text-gray-500 uppercase tracking-wider">
                                        User
                                    </th>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900
                                    bg-white divide-y divide-gray-200">
                                        @can('view', $api_log->user->id)
                                            <a href="{{ route("user.show", $api_log->user->id) }}"
                                               class="text-indigo-600 hover:text-blue-900">{{ $api_log->user->email }}
                                            </a>
                                        @endcan
                                        @cannot('view', $api_log->user->id)
                                            {{ $api_log->user->email }}
                                        @endcannot
                                    </td>
                                </tr>
                                <tr class="border-b">
                                    <th scope="col"
                                        class="px-6 py-3 bg-gray-50 text-left text-xs font-medium
                                        text-gray-500 uppercase tracking-wider">
                                        Ip address
                                    </th>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900
                                    bg-white divide-y divide-gray-200">
                                        {{$api_log->ip}}
                                    </td>
                                </tr>

                                <tr class="border-b">
                                    <th scope="col"
                                        class="px-6 py-3 bg-gray-50 text-left text-xs font-medium
                                        text-gray-500 uppercase tracking-wider">
                                        Application Id
                                    </th>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900
                                    bg-white divide-y divide-gray-200">
                                        {{ $api_log->application_id }}
                                    </td>
                                </tr>

                                <tr class="border-b">
                                    <th scope="col"
                                        class="px-6 py-3 bg-gray-50 text-left text-xs font-medium
                                        text-gray-500 uppercase tracking-wider">
                                        Api type
                                    </th>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm
                                    text-gray-900 bg-white divide-y divide-gray-200">
                                        {{ $api_log->api_type }}
                                    </td>
                                </tr>

                                <tr class="border-b">
                                    <th scope="col"
                                        class="px-6 py-3 bg-gray-50 text-left text-xs font-medium
                                        text-gray-500 uppercase tracking-wider">
                                        Http Method
                                    </th>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm
                                    text-gray-900 bg-white divide-y divide-gray-200">
                                        {{ $api_log->http_method }}
                                    </td>
                                </tr>

                                <tr class="border-b">
                                    <th scope="col"
                                        class="px-6 py-3 bg-gray-50 text-left text-xs font-medium
                                        text-gray-500 uppercase tracking-wider">
                                        Response
                                    </th>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900
                                    bg-white divide-y divide-gray-200">
                                        {{ $api_log->response }}
                                    </td>
                                </tr>
                                </tr>
                            </table>

                        </div>
                    </div>
                </div>
            </div>
            @if(isset($pushed_datas) && !empty($pushed_datas))
                <div class="block mt-8 flex flex-col">
                    <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                        <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                            <h3>Pushed datas</h3>
                            <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                                <table class="min-w-full divide-y divide-gray-200 w-full">
                                    <caption class="sr-only">Api Log - pushed data</caption>
                                    @foreach($pushed_datas as $pushed_dataID => $pushed_data)
                                        <tr class="border-b">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900
                                            bg-white divide-y divide-gray-200">
                                                @php
                                                    $class = "\App\Models\\".$api_log->model;
                                                    $route = $class::route_base
                                                @endphp
                                                <a href="{{route($route.".show", $pushed_dataID)}}">
                                                    {{ $pushed_data }}
                                                </a>
                                            </td>
                                        </tr>
                                        </tr>
                                    @endforeach
                                </table>

                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
