@php use App\Models\ListControl; @endphp
@php use App\Models\Field; @endphp
@section('title', __('fields/index.view_all_fields'))
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('fields/index.manage_fields', ['team_name' => auth()->user()->crew->name]) }}
        </h2>
    </x-slot>
    @if(!Field::hasBestDescriptiveValue())
        <div class="alert alert-danger" role="alert">
            <strong>
                {{ __('fields/index.no_best_descriptive_field') }}
            </strong>
        </div>
    @endif
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="block mb-8">
                @can("create", Field::class)
                    <a href="{{ route("fields.create") }}"
                       class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                        {{ __('fields/index.add_field') }}
                    </a>
                @endcan
                @can("viewMenu", ListControl::class)
                    <a href="{{ route("lists_control.index") }}"
                       class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">{{ __('fields/index.list_management') }}</a>
                @endcan
            </div>
            <div class="flex flex-col">
                <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                    <h3 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('fields/index.team_form', ['team_name' => auth()->user()->crew->name]) }}</h3>
                    <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">

                        <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                            <table class="min-w-full divide-y divide-gray-200">
                                <caption class="sr-only">{{ __('fields/index.team_form', ['team_name' => auth()->user()->crew->name]) }}</caption>
                                <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        #
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('fields/index.field_name') }}
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('fields/index.type') }}
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('fields/index.status') }}
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('fields/index.required') }}
                                    </th>
                                    @can("update", Field::first())
                                        <th scope="col" class="relative px-6 py-3">
                                            <span class="sr-only">
                                                {{ __('fields/index.actions') }}
                                            </span>
                                        </th>
                                    @endcan
                                </tr>
                                </thead>
                                @livewire('show-fields')
                            </table>
                        </div>
                    </div>
                </div>
            </div>


        </div>
    </div>
</x-app-layout>
<script src="https://cdn.jsdelivr.net/gh/livewire/sortable@v0.x.x/dist/livewire-sortable.js"></script>
