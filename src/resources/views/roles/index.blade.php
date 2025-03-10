@section('title', __("roles/index.roles"))
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('roles/index.roles') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="block mb-8">
                <a href="{{URL::previous() }}"
                   class="bg-gray-200 hover:bg-gray-300 text-black font-bold py-2 px-4 rounded">{{ __('common.back') }}</a>
                @can('create', \App\Models\Role::class)
                    <a href="{{ route("roles.create") }}"
                       class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">{{ __('roles/index.new_role') }}</a>
                @endcan
                @if (env("APP_DEBUG"))
                    <a href="{{ route("permissions.index") }}"
                       class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">{{ __('roles/index.permissions') }}</a>
                @endif
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
                                        {{ __('roles/index.name') }}
                                    </th>

                                    <th scope="col" class="relative px-6 py-3">
                                        <span class="sr-only">{{ __('common.action') }}</span>
                                    </th>
                                </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($roles as $role)
                                    <tr>

                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <a href="{{route("roles.show", $role->id)}}"
                                               class="text-indigo-600 hover:text-blue-900">{{$role->name}}</a>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            @can('update', $role)
                                                <a href="{{route("roles.edit", $role)}}"
                                                   class="text-indigo-600 hover:text-indigo-900">{{ __('common.edit') }}</a>
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
