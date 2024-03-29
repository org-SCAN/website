@php use App\Models\ListControl; @endphp
@php use App\Models\Field; @endphp
@section('title',"View lists")
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manage Lists') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="block mb-8">
                @can("create", ListControl::class)
                    <a href="{{ route("lists_control.create") }}"
                       class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">Add list</a>
                @endcan
                @can("viewAny", Field::class)
                    <a href="{{ route("fields.index") }}"
                       class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Manage field</a>
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
                                        List
                                    </th>
                                    @can("update", $lists[0])
                                        <th scope="col" class="relative px-6 py-3">
                                            <span class="sr-only">Action</span>
                                        </th>
                                    @endcan
                                    @can('addToList',$lists[0])
                                        <th scope="col" class="relative px-6 py-3">
                                            <span class="sr-only">Add element to list</span>
                                        </th>
                                    @endcan
                                </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($lists as $list)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @can("view", $list)
                                                <a href="{{route("lists_control.show", $list->id)}}"
                                                   class="text-indigo-600 hover:text-blue-900">{{ $list->title }}</a>
                                            @endcan
                                            @cannot("view", $list)
                                                {{ $list->title }}

                                            @endcannot
                                            @if(!$list->visible)
                                                <em class="fa fa-eye-slash"></em>
                                            @endif
                                        </td>
                                        @can("update", $list)
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                <a href="{{route("lists_control.edit", $list->id)}}"
                                                   class="text-indigo-600 hover:text-indigo-900">Edit</a>
                                            </td>
                                        @endcan
                                        @can('addToList',$list)
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                <a href="{{route("lists_control.add_to_list", $list->id)}}"
                                                   class="text-indigo-600 hover:text-indigo-900">Add element</a>
                                            </td>
                                        @endcan
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
