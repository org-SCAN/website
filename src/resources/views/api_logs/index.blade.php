@php use App\Models\User; @endphp
@section('title', __('api_logs/index.view_api_logs'))
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('api_logs/index.api_logs') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex flex-col">
                <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                    <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                        <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                            <table class="min-w-full divide-y divide-gray-200">
                                <caption class="sr-only">{{ __('api_logs/index.api_logs') }}</caption>
                                <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500
                                        uppercase tracking-wider">
                                        {{ __('api_logs/index.status') }}
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500
                                        uppercase tracking-wider">
                                        {{ __('api_logs/index.date') }}
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500
                                        uppercase tracking-wider">
                                        {{ __('api_logs/index.user') }}
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500
                                        uppercase tracking-wider">
                                        {{ __('api_logs/index.type') }}
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500
                                        uppercase tracking-wider">
                                        {{ __('api_logs/index.action') }}
                                    </th>
                                </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($logs as $log)
                                    @php($user = User::find($log["context"]["user_id"]))
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($log["context"]["response"] == "success")
                                                <em class="fas fa-check-circle  text-green-500"></em>
                                            @else
                                                <em class="fas fa-times-circle text-red-500"></em>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{$log["datetime"]}}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @can('view', $user, User::class)
                                                <a href="{{ route("user.show", $user->id) }}"
                                                   class="text-indigo-600 hover:text-blue-900">{{ $user->email ?? 'N/A' }}
                                                </a>
                                            @endcan()
                                            @cannot('view',$user, User::class)
                                                {{ $user->email ?? 'N/A' }}
                                            @endcannot

                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{$log["context"]["api_type"]}}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @can('view', $log)
                                                <a href="{{ route("api_logs.show", $log["index"]) }}"
                                                   class="text-indigo-600 hover:text-blue-900">{{ __('api_logs/index.view_detail') }}
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
