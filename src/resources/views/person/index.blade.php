@section('title','View persons')

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Persons') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="block mb-8">
                @can('create', \App\Models\Refugee::class)
                    <a href="{{ route("person.create") }}"
                       class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">Add person</a>
                @endcan
                @can('createFromJson', \App\Models\Refugee::class)
                    <a href="{{ route("person.json.create") }}"
                       class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">Add persons from
                        json</a>
                @endcan
            </div>
            <div class="flex flex-col">
                <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                    <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                        <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg p-2">
                            <table id="person" class="display">
                                <thead>
                                <tr>
                                    @foreach($fields as $field)
                                        <th>{{ $field->title }}</th>
                                    @endforeach
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($refugees as $refugee_id => $refugee)
                                    <tr>
                                        @foreach($fields ?? '' as $field)
                                            <td>
                                                @if($field->best_descriptive_value == 1)
                                                    <a href="{{route('person.show',$refugee_id)}}">{{$refugee[$field->id]}}</a>
                                                @else
                                                    {{$refugee[$field->id]?? ''}}
                                                @endif
                                            </td>
                                        @endforeach
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>


        </div>
    </div>
</x-app-layout>

