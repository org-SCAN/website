@php use App\Models\Duplicate; @endphp
@section('title', __('duplicate/index.view_duplicates'))
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('duplicate/index.manage_duplicates') }}
        </h2>
    </x-slot>

    <div class="bg-indigo-500">
        <div class="mx-auto max-w-7xl py-3 px-3 sm:px-6 lg:px-8">
            <div class="flex flex-wrap items-center justify-between">
                <div class="flex w-0 flex-1 items-center">
                    <p class="ml-3 truncate font-bold text-white" style="margin-bottom: 0px !important;">
                        <span class="text-l">{{ __('duplicate/index.last_run') }} : {{ $lastRun == null ? __('duplicate/index.never') : $lastRun->diffForHumans() }}</span>
                        <br>
                        <span class="text-l">{{ __('duplicate/index.next_due_in') }} : {{ $nextDue->diffForHumans() }}</span>
                    </p>
                </div>
                @can('compute', Duplicate::class)
                    <div class="order-3 mt-2 w-full flex-shrink-0 sm:order-2 sm:mt-0 sm:w-auto">
                        <a href="{{ route('duplicate.compute') }}"
                           class="flex items-center justify-center rounded-md border border-transparent bg-white px-4 py-2 text-sm font-bold text-indigo-600 shadow-sm hover:bg-indigo-100">{{ __('duplicate/index.force_run') }}</a>
                    </div>
                @endcan
            </div>
        </div>
    </div>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex flex-col">
                <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                    <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">


                        <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                            <h3 class="text-l text-gray-800 leading-tight m-3">{{ __('duplicate/index.duplicate') }} </h3>
                            <table class="min-w-full divide-y divide-gray-200">
                                <caption class="sr-only">{{ __('duplicate/index.possible_duplicate') }}</caption>
                                <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500
                                            uppercase tracking-wider">
                                        {{ __('duplicate/index.item1') }}
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500
                                            uppercase tracking-wider">
                                        {{ __('duplicate/index.item2') }}
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500
                                            uppercase tracking-wider">
                                        {{ __('duplicate/index.similarity') }}
                                    </th>
                                    @can('resolve', Duplicate::class)
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500
                                            uppercase tracking-wider">
                                            {{ __('duplicate/index.actions') }}
                                        </th>
                                    @endcan
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500
                                            uppercase tracking-wider">

                                    </th>
                                </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($duplicates as $duplicate)
                                    @if($duplicate->person1 == null || $duplicate->person2 == null)
                                        @continue
                                    @endif
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <a href="{{route("person.show", $duplicate->person1->id)}}"
                                               class="text-indigo-600 hover:text-blue-900">
                                                {{$duplicate->person1->best_descriptive_value}}
                                            </a>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <a href="{{route("person.show", $duplicate->person2->id)}}"
                                               class="text-indigo-600 hover:text-blue-900">
                                                {{$duplicate->person2->best_descriptive_value}}
                                            </a>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{ round($duplicate->similarity,2) }}
                                        </td>
                                        @can('resolve', $duplicate)
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <a href="{{ route("duplicate.resolve", $duplicate->id) }}"
                                                   class="text-indigo-600 hover:text-blue-900">{{ __('duplicate/index.mark_as_not_duplicated') }}</a>
                                            </td>
                                        @endcan
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($duplicate->person1->updated_at > $commandRun->started_at || $duplicate->person2->updated_at > $commandRun->started_at)
                                                <button class="btn btn-outline-danger">{{ __('duplicate/index.updated_since_last_run') }}</button>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                                <!-- More items... -->
                                </tbody>
                            </table>

                        </div>
                    </div>
                </div>
            </div>


        </div>
    </div>
</x-app-layout>
