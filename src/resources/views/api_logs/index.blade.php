@php use App\Models\User; @endphp
@section('title',"View API logs")
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Api Logs') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex flex-col">
                <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                    <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                        <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                            <table class="min-w-full divide-y divide-gray-200">
                                <caption class="sr-only">Api Logs</caption>
                                <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500
                                        uppercase tracking-wider">
                                        Status
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500
                                        uppercase tracking-wider">
                                        Date
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500
                                        uppercase tracking-wider">
                                        User
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500
                                        uppercase tracking-wider">
                                        Type
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500
                                        uppercase tracking-wider">
                                        Action
                                    </th>
                                </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($logs as $log)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($log->response == "success")
                                                <em class="fas fa-check-circle  text-green-500"></em>
                                            @else
                                                <em class="fas fa-times-circle text-red-500"></em>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{$log->created_at}}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @can('view', $log->user, User::class)
                                                <a href="{{ route("user.show", $log->user->id) }}"
                                                   class="text-indigo-600 hover:text-blue-900">{{ $log->user->email }}
                                                </a>
                                            @endcan()
                                            @cannot('view', $log->user,User::class)
                                                {{ $log->user->email }}
                                            @endcannot

                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{$log->api_type}}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @can('view', $log)
                                                <a href="{{ route("api_logs.show", $log->id) }}"
                                                   class="text-indigo-600 hover:text-blue-900">View detail
                                                </a>
                                            @endcan
                                        </td>
                                    </tr>
                                @endforeach
                                <!-- More items... -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>


        </div>
    </div>
</x-app-layout>
