@section('title',"View ".$lists_control->title." details")
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            <b>{{ $lists_control->title }}</b> details
        </h2>
    </x-slot>
    <div>
        <div class="max-w-6xl mx-auto py-10 sm:px-6 lg:px-8">
            <div class="block mb-8">
                <form action="{{route('lists_control.destroy', $lists_control->id)}}" method="POST"
                      class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                    <a href="{{ route('lists_control.index')}}"
                       class="bg-gray-200 hover:bg-gray-300 text-black font-bold py-2 px-4 rounded">Lists</a>
                    <a href="{{ route('lists_control.edit', $lists_control->id) }}"
                       class="bg-blue-200 hover:bg-blue-300 text-black font-bold py-2 px-4 rounded">Edit</a>
                    <a href="{{ route('lists_control.add_to_list', $lists_control->id) }}"
                       class="bg-green-200 hover:bg-green-300 text-black font-bold py-2 px-4 rounded">Add an element to
                        the list</a>
                    @method('DELETE')
                    @csrf
                    <button type="submit"
                            class="flex-shrink-0 bg-red-200 hover:bg-red-300 text-black font-bold py-2 px-4 rounded">
                        Delete
                    </button>
                    @error("deleteList")
                    <p class="text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </form>
            </div>
            <div class="flex flex-col">
                <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                    <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                        <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                            <table class="min-w-full divide-y divide-gray-200 w-full">
                                <thead>
                                @foreach($list_structure as $field)
                                    @if($lists_control->displayed_value == $field->field)

                                    <th scope="col" class="px-6 py-3 bg-gray-50 text-left text-xs font-bold text-gray-500 uppercase tracking-wider"><b>{{$field->field}}*</b></th>
                                    @else
                                    <th scope="col" class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{$field->field}}</th>
                                    @endif
                                @endforeach

                                <th scope="col" class="px-6 py-3 bg-gray-50 text-left text-xs font-bold text-gray-500 uppercase tracking-wider"></th>
                                </thead>
                                <tbody>
                                @foreach($list_content as $list_elem)
                                    <tr class="border-b">
                                        @foreach($list_structure as $field)
                                            @if($lists_control->displayed_value == $field->field)
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 bg-white divide-y divide-gray-200"><b>{{ $list_elem[$field->field] }}</b></td>
                                            @else
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 bg-white divide-y divide-gray-200">{{ $list_elem[$field->field] }}</td>
                                            @endif
                                        @endforeach
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 bg-white divide-y divide-gray-200">


                                            <form action="{{route("lists_control.deleteListElem", [$lists_control, $list_elem->id])}}" method="POST">
                                                <a href="{{route("lists_control.editListElem", [$lists_control, $list_elem->id])}}">
                                                    <i class="fa fa-pen text-blue-500 hover:text-blue-700" aria-hidden="true"></i>
                                                </a>
                                                &nbsp; &nbsp;
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="border-0">
                                                    <i class='fa fa-trash text-red-500 hover:text-red-700' aria-hidden='true'></i>
                                                </button>
                                                @error("delete.".$list_elem->id)
                                                <p class="text-sm text-red-600">{{ $message }}</p>
                                                @enderror
                                            </form>
                                        </td>
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
