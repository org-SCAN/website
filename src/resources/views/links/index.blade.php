@section('title',"View all relations")
@livewireStyles
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Relations') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="block mb-8">
                @can('create', \App\Models\Link::class)
                    <a href="{{ route("links.create") }}"
                       class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">Add relation</a>
                @endcan
                @can('createFromJson', \App\Models\Link::class)
                    <a href="{{ route("links.create_from_json") }}"
                       class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">Add relation from
                        json</a>
                @endcan
            </div>
            <div class="flex flex-col">
                <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                    <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                        {{--<div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg p-2">
                            <livewire:links-datatables
                                per-page="25"
                                exportable
                            />
                        </div>--}}
                        <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg p-2">
                            <table id="person" class="display">
                                <thead>
                                <tr>
                                    <th>From</th>
                                    <th>Relation</th>
                                    <th>To</th>
                                    @can('update', $links->first())
                                        <th></th>
                                    @endcan
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($links as $link)
                                    <tr>
                                        <td>
                                            <a href="{{route('person.show',  $link->refugeeFrom->id)}}"> {{ $link->refugeeFrom->best_descriptive_value }}</a>
                                        </td>
                                        <td>{{ $link->relation }}</td>
                                        <td>
                                            <a href="{{route('person.show',  $link->refugeeTo->id)}}">{{ $link->refugeeTo->best_descriptive_value }}</a>
                                        </td>
                                        @can('update', $link)
                                            <td><a href="{{route('links.edit',  $link->id)}}">Edit</a></td>
                                        @endcan
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
