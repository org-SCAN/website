@section('title','View persons')
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manage Persons') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="block mb-8">
                @if(Auth::user()->hasPermission("person.create"))
                    <a href="{{ route("person.create") }}"
                       class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">Add person</a>
                @endif
                @if(Auth::user()->hasPermission("person.json.create"))
                    <a href="{{ route("person.json.create") }}"
                       class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">Add persons from
                        json</a>
                @endif
            </div>
            <div class="flex flex-col">
                <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                    <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                        <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg p-2">

                            <livewire:refugees-datatables
                                per-page="25"
                                exportable
                            />
                        </div>
                    </div>
                </div>
            </div>


        </div>
    </div>
</x-app-layout>
