<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Log Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <div class="mb-4">
                    <a href="{{ route('logs.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded">
                        Back to Logs
                    </a>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <h3 class="text-lg font-semibold mb-2">Basic Information</h3>
                        <dl>
                            <dt class="font-medium">Time</dt>
                            <dd class="mb-2">{{ $log['datetime']->format('Y-m-d H:i:s') }}</dd>

                            <dt class="font-medium">Level</dt>
                            <dd class="mb-2">{{ $log['level'] }}</dd>

                            <dt class="font-medium">Message</dt>
                            <dd class="mb-2">{{ $log['message'] }}</dd>
                        </dl>
                    </div>

                    <div>
                        <h3 class="text-lg font-semibold mb-2">Request Details</h3>
                        <dl>
                            <dt class="font-medium">Method</dt>
                            <dd class="mb-2">{{ $log['context']['method'] ?? 'N/A' }}</dd>

                            <dt class="font-medium">Path</dt>
                            <dd class="mb-2">{{ $log['context']['path'] ?? 'N/A' }}</dd>

                            <dt class="font-medium">IP</dt>
                            <dd class="mb-2">{{ $log['context']['ip'] ?? 'N/A' }}</dd>

                            <dt class="font-medium">User ID</dt>
                            <dd class="mb-2">{{ $log['context']['user_id'] ?? 'N/A' }}</dd>

                            <dt class="font-medium">Status</dt>
                            <dd class="mb-2">{{ $log['context']['status'] ?? 'N/A' }}</dd>
                        </dl>
                    </div>
                </div>

                @if(!empty($log['context']['request_body']))
                    <div class="mt-4">
                        <h3 class="text-lg font-semibold mb-2">Request Body</h3>
                        <pre
                            class="bg-gray-100 p-4 rounded">{{ json_encode($log['context']['request_body'], JSON_PRETTY_PRINT) }}</pre>
                    </div>
                @endif

                @if(!empty($log['context']['response']))
                    <div class="mt-4">
                        <h3 class="text-lg font-semibold mb-2">Response</h3>
                        <pre
                            class="bg-gray-100 p-4 rounded">{{ json_encode($log['context']['response'], JSON_PRETTY_PRINT) }}</pre>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
