@section('title',"Manage users")
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Users') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="block mb-8">
                <a href="{{ route("user.create") }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">Add user</a>
            </div>
            <div class="flex flex-col">
                <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                    <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                        <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Name
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Email
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Role
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Team
                                    </th>

                                    <th scope="col" class="relative px-6 py-3">
                                        <span class="sr-only">Action</span>
                                    </th>
                                </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($users as $user)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <a href="{{route("user.show", $user->id)}}" class="text-indigo-600 hover:text-blue-900">{{$user->name}}</a>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <a href="{{route("user.show", $user->id)}}" class="text-indigo-600 hover:text-blue-900">{{$user->email}}</a>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <a href="{{route("user.show", $user->id)}}" class="text-indigo-600 hover:text-blue-900">{{$user->role->role}}</a>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <a href="{{route("crew.show", $user->crew->id)}}" class="text-indigo-600 hover:text-blue-900">{{$user->crew->name}}</a>
                                        </td>

                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <a href="{{route("user.edit", $user->id)}}" class="text-indigo-600 hover:text-indigo-900">Edit</a>
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
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="block mt-4 mb-8">
                <h4>Grant user permissions</h4>
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
                                        Name
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Grant
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Reject
                                    </th>
                                </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($request_roles as $request_role)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <a href="{{route("user.show", $request_role->getUserId())}}"
                                               class="text-indigo-600 hover:text-blue-900">{{$request_role->user}}</a>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <a href="{{route("user.grant_role", $request_role->id)}}"
                                               class="text-green-600 hover:text-green-900">Grant {{$request_role->role}}
                                                role</a>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <a href="{{route("user.reject_role", $request_role->id)}}"
                                               class="text-red-600 hover:text-red-900">Reject {{$request_role->role}}
                                                role</a>
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
