@section('title','View '.$person->best_descriptive_value." details")
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            <b>{{ $person->best_descriptive_value }}</b> details
        </h2>
    </x-slot>

    <div>
        <div class="max-w-6xl mx-auto py-10 sm:px-6 lg:px-8">
            <div class="block mb-8">
                <form action="{{route('person.destroy', $person->id)}}" method="POST"
                      class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                    <a href="{{ route('person.index') }}"
                       class="bg-gray-200 hover:bg-gray-300 text-black font-bold py-2 px-4 rounded">Back to list</a>
                    @can("update", $person)
                        <a href="{{ route('person.edit', $person->id) }}"
                           class="bg-blue-200 hover:bg-blue-300 text-black font-bold py-2 px-4 rounded">Edit</a>
                    @endcan

                    @can("delete", $person)
                        @method('DELETE')
                        @csrf
                        <button type="submit"

                                class="flex-shrink-0 bg-red-200 hover:bg-red-300 text-black font-bold py-2 px-4 rounded">
                            Delete
                        </button>
                    @endcan
                </form>
            </div>
            <div class="flex flex-col">
                <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                    <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                        <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                            <table class="min-w-full divide-y divide-gray-200 w-full">
                                @foreach($person->fields as $field)
                                    <tr class="border-b">
                                        <th scope="col" class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{$field->title}}
                                        </th>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 bg-white divide-y divide-gray-200">
                                            {{ $field->getValue() }}
                                        </td>
                                    </tr>
                                @endforeach
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            @can("viewMenu", \App\Models\Link::class)
                <div class="block mt-8 flex flex-col">
                    <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                        <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                            <h2 class="font-semibold text-xl text-gray-800 leading-tight mb-8">
                                <b>{{ $person->full_name }}</b> relations
                            </h2>
                            <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                                <div class="block mb-8 mt-4">
                                    <a href="{{ route('cytoscape.index', ["from"=>$person->id]) }}"
                                       class="bg-gray-200 hover:bg-gray-300 hover:text-black text-black font-bold py-2 px-4 rounded m-3">View
                                        relations in graph</a>
                                </div>
                                <table class="min-w-full divide-y divide-gray-200 w-full">
                                    <thead>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-800 uppercase tracking-wider">
                                        From
                                    </th>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-800 uppercase tracking-wider">
                                        Relation
                                    </th>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-800 uppercase tracking-wider">
                                        To
                                    </th>
                                    @can("update")
                                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-800 uppercase tracking-wider"></th>
                                    @endcan
                                    </thead>
                                    @foreach($person->relations as $sided_relations)
                                        @foreach($sided_relations as $link)
                                            <tr class="border-b">
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 bg-white divide-y divide-gray-200">
                                                    <a href="{{route("person.show", $link->pivot->from)}}">{{$link->pivot->refugeeFrom->best_descriptive_value }}</a>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 bg-white divide-y divide-gray-200">
                                                    {{ $link->name }}
                                                    <small>{{$link->detail}}</small>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 bg-white divide-y divide-gray-200">
                                                    <a href="{{route("person.show", $link->pivot->to)}}">{{$link->pivot->refugeeTo->best_descriptive_value}}</a>
                                                </td>
                                                @can("update")
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 bg-white divide-y divide-gray-200">
                                                        <a href="{{route("links.edit", $link->pivot->id)}}">Edit</a>
                                                    </td>
                                                @endcan
                                            </tr>
                                        @endforeach
                                    @endforeach
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            @endcan
        </div>
    </div>
</x-app-layout>
