@section('title',"Show ".$link->from."and".$link->to." relation")
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            <b>{{ $field->title }}</b> details
        </h2>
    </x-slot>

    <div>
        <div class="max-w-6xl mx-auto py-10 sm:px-6 lg:px-8">
            <div class="block mb-8">
                <form action="{{route('fields.destroy', $field->id)}}" method="POST" class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                    <a href="{{ route('fields.index') }}" class="bg-gray-200 hover:bg-gray-300 text-black font-bold py-2 px-4 rounded">Back to list</a>
                    @if(Auth::user()->hasPermission("links.create"))
                    <a href="{{ route('fields.edit', $field->id) }}" class="bg-blue-200 hover:bg-blue-300 text-black font-bold py-2 px-4 rounded">Edit</a>
                    @endif
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
                                @foreach($display_elements as $element_key => $element_value)
                                    <tr class="border-b">
                                        <th scope="col" class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{$element_value}}
                                        </th>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 bg-white divide-y divide-gray-200">
                                            {{ $field->$element_key }}
                                        </td>
                                    </tr>
                                @endforeach
                                <tr class="border-b">
                                    <th scope="col" class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Associate list
                                    </th>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-blue-900 bg-white divide-y divide-gray-200">
                                        @if(!empty($field->linked_list))
                                            <a href="{{route("lists_control.show", $field->getLinkedListId())}}">{{ $field->linked_list }}</a>
                                        @else
                                            {{ $field->linked_list }}
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="block mt-8">
                <a href="{{ route('fields.index') }}" class="bg-gray-200 hover:bg-gray-300 text-black font-bold py-2 px-4 rounded">Back to list</a>
            </div>
        </div>
    </div>
</x-app-layout>
