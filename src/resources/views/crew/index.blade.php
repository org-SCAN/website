@php use App\Models\Crew; @endphp
@section('title',"Manage teams")
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Teams') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @can('create', Crew::class)
                <div class="block mb-8">
                    <a href="{{ route("crew.create") }}"
                       class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">Add a team</a>
                </div>
            @endcan
            <div class="flex flex-col">
                <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                    <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                        <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                            <table class="min-w-full divide-y divide-gray-200">
                                <caption class="sr-only">Crews</caption>
                                <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium
                                        text-gray-500 uppercase tracking-wider">
                                        Name
                                    </th>

                                    <th scope="col" class="relative px-6 py-3">
                                        <span class="sr-only">Action</span>
                                    </th>
                                </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($crews as $crew)
                                    <tr>

                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @can('view', $crew)
                                                <a href="{{ route("crew.show", $crew->id) }}"
                                                   class="text-indigo-600 hover:text-blue-900">{{ $crew->name }}</a>

                                            @endcan
                                            @cannot('view', $crew)
                                                {{ $crew->name }}
                                            @endcannot
                                        </td>

                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            @can('update', $crew)
                                                <a href="{{route("crew.edit", $crew->id)}}"
                                                   class="text-indigo-600 hover:text-indigo-900">Edit</a>
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
