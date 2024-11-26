@section('title', __('api_logs/show.show_details', ['id' => $log['index']]))
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('api_logs/show.detail_of_push') }}
            <strong> {{ $log['index'] }}</strong>
        </h2>
    </x-slot>

    <div>
        <div class="max-w-6xl mx-auto py-10 sm:px-6 lg:px-8">
            <div class="block mb-8">
                <a href="{{ route('api_logs.index') }}"
                   class="bg-gray-200 hover:bg-gray-300 text-black font-bold py-2 px-4 rounded">
                    {{ __('api_logs/show.back_to_list') }}
                </a>
            </div>
            <div class="block mt-8 flex flex-col">
                <!-- ... (le reste de votre code reste inchangé) ... -->
                <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                    <!-- ... -->
                    <table class="min-w-full divide-y divide-gray-200 w-full">
                        <caption class="sr-only">
                            {{ __('api_logs/show.api_log_detail') }}
                        </caption>
                        <tr class="border-b">
                            <th scope="col"
                                class="px-6 py-3 bg-gray-50 text-left text-xs font-medium
                                text-gray-500 uppercase tracking-wider">
                                {{ __('api_logs/show.date') }}
                            </th>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900
                            bg-white divide-y divide-gray-200">
                                {{ $log['datetime'] }}
                            </td>
                        </tr>

                        <tr class="border-b">
                            <th scope="col"
                                class="px-6 py-3 bg-gray-50 text-left text-xs font-medium
                                text-gray-500 uppercase tracking-wider">
                                {{ __('api_logs/show.user') }}
                            </th>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900
                            bg-white divide-y divide-gray-200">
                                @php
                                    $userId = $log['context']['user_id'] ?? null;
                                    $userEmail = $log['context']['user_email'] ?? 'N/A';
                                @endphp
                                @can('view', [User::class, $userId])
                                    <a href="{{ route("user.show", $userId) }}"
                                       class="text-indigo-600 hover:text-blue-900">{{ $userEmail }}
                                    </a>
                                @else
                                    {{ $userEmail }}
                                @endcan
                            </td>
                        </tr>
                        <!-- Autres champs comme IP, application_id, api_type, etc. -->
                        <tr class="border-b">
                            <th scope="col"
                                class="px-6 py-3 bg-gray-50 text-left text-xs font-medium
                                text-gray-500 uppercase tracking-wider">
                                {{ __('api_logs/show.ip_address') }}
                            </th>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900
                            bg-white divide-y divide-gray-200">
                                {{ $log['context']['ip'] ?? 'N/A' }}
                            </td>
                        </tr>
                        <!-- Ajoutez d'autres champs si nécessaire -->
                    </table>
                </div>
            </div>
            <!-- Si vous avez des données poussées, vous pouvez les afficher ici -->
            @if(isset($log['context']['pushed_datas']) && !empty($log['context']['pushed_datas']))
                <div class="block mt-8 flex flex-col">
                    <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                        <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                            <h3>Pushed datas</h3>
                            <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                                <table class="min-w-full divide-y divide-gray-200 w-full">
                                    <caption class="sr-only">{{ __('api_logs/show.api_log_pushed_data') }}</caption>
                                    @foreach($log['context']['pushed_datas'] as $pushed_dataID => $pushed_data)
                                        <tr class="border-b">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900
                                            bg-white divide-y divide-gray-200">
                                                <a href="{{ route('route_name.show', $pushed_dataID) }}">
                                                    {{ $pushed_data }}
                                                </a>
                                            </td>
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
