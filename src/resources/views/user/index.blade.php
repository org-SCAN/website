@section('title', __("user/index.title"))
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __("user/index.section_title") }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="block mb-8">
                @can('create', \App\Models\User::class)
                    <a href="{{ route("user.create") }}"
                       class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">{{ __("user/index.add_user") }}</a>
                @endcan
                @can('viewAny', \App\Models\Role::class)
                    <a href="{{ route("roles.index") }}"
                       class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">{{ __("user/index.roles") }}</a>
                @endcan
            </div>
            <div class="flex flex-col">
                <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                    <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                        <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __("user/index.name") }}
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __("user/index.email") }}
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __("user/index.role") }}
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __("user/index.team") }}
                                    </th>

                                    <th scope="col" class="relative px-6 py-3">
                                        <span class="sr-only">{{ __("common.action") }}</span>
                                    </th>
                                </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($users as $user)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @can('view', $user)
                                                <a href="{{route("user.show", $user->id)}}"
                                                   class="text-indigo-600 hover:text-blue-900">{{ $user->name }}</a>
                                            @endcan
                                            @cannot('view', $user)
                                                {{ $user->name }}
                                            @endcannot
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <a href="{{route("user.show", $user->id)}}"
                                               class="text-indigo-600 hover:text-blue-900">{{$user->email}}</a>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <a href="{{route("roles.show", $user->role)}}"
                                               class="text-indigo-600 hover:text-blue-900">{{$user->role->name}}</a>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <a href="{{route("crew.show", $user->crew->id)}}"
                                               class="text-indigo-600 hover:text-blue-900">{{$user->crew->name}}</a>
                                        </td>

                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            @can('update', $user)
                                                <a href="{{route("user.edit", $user->id)}}"
                                               class="text-indigo-600 hover:text-indigo-900">{{ __("common.edit") }}</a>
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
        @can('grantRole', \App\Models\User::class)
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="block mt-4 mb-8">
                <h4>{{ __("user/index.grant_permission") }}</h4>
            </div>
            <div class="flex flex-col">
                <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                    <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                        <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __("user/index.name") }}
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __("user/index.grant") }}
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __("user/index.reject") }}
                                    </th>
                                </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($request_roles as $request_role)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <a href="{{route("user.show", $request_role->user->id)}}"
                                               class="text-indigo-600 hover:text-blue-900">{{$request_role->user->name}}</a>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <a href="{{route("user.grant_role", $request_role->id)}}"
                                               class="text-green-600 hover:text-green-900">
                                                {!! __("user/index.grant_role", ["role_name" => $request_role->role->name]) !!}
                                            </a>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <a href="{{route("user.reject_role", $request_role->id)}}"
                                               class="text-red-600 hover:text-red-900">
                                                {!! __("user/index.reject_role", ["role_name" => $request_role->role->name]) !!}
                                            </a>
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
        @endcan
    </div>
</x-app-layout>
