@php use App\Models\Source; @endphp
@php use App\Models\ListSourceType; @endphp
@php use App\Models\Link; @endphp
@section('title', __('person/show.view_details', ['name' => $person->best_descriptive_value]))
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            <strong>{{ $person->best_descriptive_value }}</strong> {{ __('person/show.details') }}
        </h2>
    </x-slot>

    <div>
        <div class="max-w-6xl mx-auto py-10 sm:px-6 lg:px-8">
            <div class="block mb-8">
                <form action="{{route('person.destroy', $person->id)}}" method="POST"
                      class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                    <a href="{{ route('person.index') }}"
                       class="bg-gray-200 hover:bg-gray-300 text-black font-bold py-2 px-4 rounded">
                        {{ __('common.back') }}
                    </a>
                    @can("update", $person)
                        <a href="{{ route('person.edit', $person->id) }}"
                           class="bg-blue-200 hover:bg-blue-300 text-black font-bold py-2 px-4 rounded">
                            {{ __('common.edit') }}
                        </a>
                    @endcan

                    @can("delete", $person)
                        @method('DELETE')
                        @csrf
                        <button type="submit"
                                class="flex-shrink-0 bg-red-200 hover:bg-red-300
                                text-black font-bold py-2 px-4 rounded">
                            {{ __('common.delete') }}
                        </button>
                    @endcan
                </form>
                @can('create', Source::class)
                    <form action="{{route('source.store')}}" method="POST"
                          class="w-full md:w-1/2 px-3 mb-6 md:mb-0 mt-2">
                        @csrf
                        <input type="hidden" name="reference" value="{{$person->id}}">
                        <input type="hidden" name="name" value="{{$person->best_descriptive_value}}">
                        <input type="hidden" name="source_type_id"
                               value="{{ ListSourceType::firstWhere('name', 'Person')->id }}">
                        <button type="submit"
                                class="flex-shrink-0 bg-green-200 hover:bg-green-300
                                text-black font-bold py-2 px-4 rounded">
                            {{ __('person/show.add_as_source') }}
                        </button>
                @endcan
            </div>
            <div class="flex flex-col">
                <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                    <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                        <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                            <table class="min-w-full divide-y divide-gray-200 w-full">
                                <caption class="sr-only">{{ __('person/show.item_details') }}</caption>
                                @foreach($person->fields as $field)
                                    <tr class="border-b">
                                        <th scope="col"
                                            class="px-6 py-3 bg-gray-50 text-left text-xs font-medium
                                            text-gray-500 uppercase tracking-wider">
                                            {{$field->title}}
                                        </th>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm
                                        text-gray-900 bg-white divide-y divide-gray-200">
                                            @livewire('forms.show', [
                                            'field' => $field
                                            ])
                                        </td>
                                    </tr>
                                @endforeach
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            @can("viewMenu", Link::class)
                <div class="block mt-8 mb-8 flex flex-col">
                    <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                        <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                            <h2 class="font-semibold text-xl text-gray-800 leading-tight mb-8">
                                <strong>{{ $person->full_name }}</strong> {{ __('person/show.relation') }}
                            </h2>
                            <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                                <div class="block mb-8 mt-4">
                                    <a href="{{ route('cytoscape.index', ["from"=>$person->id]) }}"
                                       class="bg-gray-200 hover:bg-gray-300 hover:text-black text-black
                                       font-bold py-2 px-4 rounded m-3">
                                        {{ __('person/show.view_relations_in_graph') }}
                                    </a>
                                </div>
                                <table class="min-w-full divide-y divide-gray-200 w-full">
                                    <caption class="sr-only">{{ __('person/show.item_relation') }}</caption>
                                    <thead>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium
                                        text-gray-800 uppercase tracking-wider">
                                        {{ __('person/show.from') }}
                                    </th>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium
                                        text-gray-800 uppercase tracking-wider">
                                        {{ __('person/show.relation') }}
                                    </th>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium
                                        text-gray-800 uppercase tracking-wider">
                                        {{ __('person/show.to') }}
                                    </th>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium
                                        text-gray-800 uppercase tracking-wider"></th>
                                    </thead>
                                    @foreach($person->relations as $sided_relations)
                                        @foreach($sided_relations as $link)
                                            <tr class="border-b">
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900
                                                bg-white divide-y divide-gray-200">
                                                    <a href="{{route("person.show", $link->pivot->from)}}">
                                                        {{$link->pivot->refugeeFrom->best_descriptive_value }}
                                                    </a>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm
                                                text-gray-900 bg-white divide-y divide-gray-200">
                                                    {{ $link->name }}
                                                    <small>{{$link->detail}}</small>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm
                                                text-gray-900 bg-white divide-y divide-gray-200">
                                                    <a href="{{route("person.show", $link->pivot->to)}}">
                                                        {{ $link->pivot->refugeeTo->best_descriptive_value }}
                                                    </a>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm
                                                text-gray-900 bg-white divide-y divide-gray-200">
                                                    <a href="{{route("links.edit", $link->pivot->id)}}">
                                                        {{ __('person/show.edit') }}
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endforeach
                                    @if(Link::$quickAdd)
                                        @can('create', Link::class)
                                            <tr class="border-b mb-4">
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                            <tr class="border-b mt-4">
                                                <td class="px-6 py-4 whitespace-nowrap text-sm
                                                text-gray-900 bg-gray-100 divide-y divide-gray-300">
                                                    <a href="{{ route("person.show", $person->id) }}">
                                                        {{ $person->best_descriptive_value }}
                                                    </a>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900
                                                bg-gray-100 divide-y divide-gray-300"></td>

                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900
                                                bg-gray-100 divide-y divide-gray-300">
                                                    <a href="{{ route("links.create", ["origin" => 'from', 'refugee' => $person]) }}"
                                                       class="bg-green-200 hover:bg-green-300
                                                       text-black font-bold py-2 px-4 rounded">
                                                        {{ __('person/show.add_to') }}
                                                    </a>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900
                                                bg-gray-100 divide-y divide-gray-300"></td>
                                            </tr>
                                            <tr class="border-b">
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900
                                                bg-gray-100 divide-y divide-gray-300">
                                                    <a href="{{ route("links.create", ["origin" => 'to', 'refugee' => $person]) }}"
                                                       class="bg-green-200 hover:bg-green-300 text-black
                                                       font-bold py-2 px-4 rounded">
                                                        {{ __('person/show.add_from') }}
                                                    </a>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900
                                                bg-gray-100 divide-y divide-gray-300"></td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900
                                                bg-gray-100 divide-y divide-gray-300">
                                                    <a href="{{ route("person.show", $person->id) }}">
                                                        {{ $person->best_descriptive_value }}
                                                    </a>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900
                                                bg-gray-100 divide-y divide-gray-300"></td>
                                            </tr>
                                        @endcan
                                    @endif
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            @endcan
            @if($markers->count() > 0)
            <div class="block mb-8">
                <div class="-my-2 sm:-mx-6 lg:-mx-8">
                    <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                        <div style="height: 400px" class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                            @map([
                                'lat' => $center['lat'],
                                'lng' => $center['lng'],
                                'zoom' => 2,
                                'markers' => $markers
                            ])
                            @mapscripts
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</x-app-layout>
