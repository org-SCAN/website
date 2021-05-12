@section('title',"View ".$list->title." details")
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            <b>{{ $list->title }}</b> details
        </h2>
    </x-slot>

    <div>
        <div class="max-w-6xl mx-auto py-10 sm:px-6 lg:px-8">
            <div class="block mb-8">
                <a href="{{ URL::previous()}}" class="bg-gray-200 hover:bg-gray-300 text-black font-bold py-2 px-4 rounded">Back</a>
            <!-- TODO : handle this because it could be "dangerous"
                <form action="{{route('lists_control.destroy', $list->id)}}" method="POST" class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                    <a href="{{ URL::previous()}}" class="bg-gray-200 hover:bg-gray-300 text-black font-bold py-2 px-4 rounded">Back</a>
                    <a href="{{ route('lists_control.edit', $list->id) }}" class="bg-blue-200 hover:bg-blue-300 text-black font-bold py-2 px-4 rounded">Edit</a>
                    @method('DELETE')
            @csrf
                <button type="submit" class="flex-shrink-0 bg-red-200 hover:bg-red-300 text-black font-bold py-2 px-4 rounded">Delete</button>
            </form>
-->
            </div>
            <div class="flex flex-col">
                <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                    <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                        <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                            <table class="min-w-full divide-y divide-gray-200 w-full">
                                <thead>
                                @foreach(array_keys($list_content[0]) as $list_key)
                                    @if(!in_array($list_key, ["id", "created_at", "updated_at", "deleted"]))
                                        @if($list_key == $list->displayed_value)
                                            <th scope="col" class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-800 uppercase tracking-wider"><b>{{$list_key}}*</b></th>
                                        @else
                                            <th scope="col" class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{$list_key}}</th>
                                        @endif
                                    @endif
                                @endforeach
                                </thead>
                                <tbody>
                                @foreach($list_content as $list_elem)
                                    <tr class="border-b">
                                        @foreach($list_elem as $elem_key => $elem)
                                            @if(!in_array($elem_key, ["id", "created_at", "updated_at", "deleted"]))
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 bg-white divide-y divide-gray-200">{{$elem}}</td>
                                            @endif
                                        @endforeach
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="block mt-8">
                <small>* : current displayed value</small>
            </div>
            <div class="block mt-8">
                <a href="{{ URL::previous()}}" class="bg-gray-200 hover:bg-gray-300 text-black font-bold py-2 px-4 rounded">Back</a>
            </div>
        </div>
    </div>
</x-app-layout>
