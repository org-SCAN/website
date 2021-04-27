<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            <b>{{ $refugee->full_name }}</b> details
        </h2>
    </x-slot>

    <div>
        <div class="max-w-6xl mx-auto py-10 sm:px-6 lg:px-8">
            <div class="block mb-8">
                <form action="{{route('manage_refugees.destroy', $refugee->id)}}" method="POST" class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                    <a href="{{ route('manage_refugees.index') }}" class="bg-gray-200 hover:bg-gray-300 text-black font-bold py-2 px-4 rounded">Back to list</a>
                    <a href="{{ route('manage_refugees.edit', $refugee->id) }}" class="bg-blue-200 hover:bg-blue-300 text-black font-bold py-2 px-4 rounded">Edit</a>
                    @method('DELETE')
                    @csrf
                    <button type="submit" class="flex-shrink-0 bg-red-200 hover:bg-red-300 text-black font-bold py-2 px-4 rounded">Delete</button>
                </form>
            </div>
            <div class="flex flex-col">
                <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                    <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                        <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                            <table class="min-w-full divide-y divide-gray-200 w-full">
                                @foreach($fields as $field)
                                    <tr class="border-b">
                                        <th scope="col" class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{$field->title}}
                                        </th>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 bg-white divide-y divide-gray-200">
                                            @php
                                                $elm = $field->label
                                            @endphp
                                            {{ $refugee->$elm }}
                                        </td>
                                    </tr>
                                @endforeach
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="block mt-8 flex flex-col">
                <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                    <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                        <h2 class="font-semibold text-xl text-gray-800 leading-tight mb-8">
                            <b>{{ $refugee->full_name }}</b> relations
                        </h2>
                        <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                            <table class="min-w-full divide-y divide-gray-200 w-full">
                                <thead>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-800 uppercase tracking-wider">From</th>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-800 uppercase tracking-wider">Relation</th>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-800 uppercase tracking-wider">To</th>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-800 uppercase tracking-wider"></th>
                                </thead>
                                @foreach($links as $link)
                                    <tr class="border-b">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 bg-white divide-y divide-gray-200">
                                            <a href="{{route("manage_refugees.show", $link->getFromId())}}">{{$link->from }}</a>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 bg-white divide-y divide-gray-200">
                                            {{ $link->relation }}
                                            <small>{{$link->detail}}</small>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 bg-white divide-y divide-gray-200">
                                            <a href="{{route("manage_refugees.show", $link->getToId())}}">{{$link->to }}</a>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 bg-white divide-y divide-gray-200">
                                            <a href="{{route("links.edit", $link->id)}}">Edit</a>
                                        </td>

                                        <!--
                                        <th scope="col" class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-blue-800 uppercase tracking-wider">
                                        @php($relation_name = ($link->getFromId() == $refugee->id ? "to" : "from"))
                                        @php($relation_id = "get".ucfirst($relation_name)."Id")
                                            <a href="{{route("manage_refugees.show", $link->$relation_id())}}">{{$link->$relation_name }}</a>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 bg-white divide-y divide-gray-200">
                                            {{ $link->relation }}
                                        </td> --!>
                                    </tr>
                                @endforeach
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
