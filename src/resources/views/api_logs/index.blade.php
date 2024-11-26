<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('API Logs') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <div class="mb-4">
                    <select onchange="window.location.href='?date=' + this.value">
                        @foreach($dates as $date)
                            <option value="{{ $date }}" {{ $selectedDate === $date ? 'selected' : '' }}>
                                {{ $date }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                    <tr>
                        <th class="px-6 py-3 bg-gray-50 text-left">Time</th>
                        <th class="px-6 py-3 bg-gray-50 text-left">Level</th>
                        <th class="px-6 py-3 bg-gray-50 text-left">User</th>
                        <th class="px-6 py-3 bg-gray-50 text-left">Path</th>
                        <th class="px-6 py-3 bg-gray-50 text-left">Status</th>
                        <th class="px-6 py-3 bg-gray-50 text-left">Action</th>
                    </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($logs as $log)
                        <tr>
                            <td class="px-6 py-4">{{ $log['datetime']->format('H:i:s') }}</td>
                            <td class="px-6 py-4">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                        {{ $log['level'] === 'error' ? 'bg-red-100 text-red-800' :
                                           ($log['level'] === 'warning' ? 'bg-yellow-100 text-yellow-800' :
                                            'bg-green-100 text-green-800') }}">
                                        {{ $log['level'] }}
                                    </span>
                            </td>
                            <td class="px-6 py-4">{{ $log['context']['user_id'] ?? 'N/A' }}</td>
                            <td class="px-6 py-4">{{ $log['context']['path'] ?? '' }}</td>
                            <td class="px-6 py-4">{{ $log['context']['status'] ?? '' }}</td>
                            <td class="px-6 py-4">
                                <a href="{{ route('logs.show', ['date' => $selectedDate, 'id' => $log['context']['request_id'] ?? '']) }}"
                                   class="text-indigo-600 hover:text-indigo-900">
                                    View Details
                                </a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
