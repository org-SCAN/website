@section('title', __('fields/show.view_field_details', ['field_title' => $field->title]))
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            <b>{{ $field->title }}</b> {{ __('fields/show.details') }}
        </h2>
    </x-slot>

    <div>
        <div class="max-w-6xl mx-auto py-10 sm:px-6 lg:px-8">
            <div class="block mb-8">
                @can("delete", $field)
                    <form action="{{route('fields.destroy', $field->id)}}" method="POST"
                          class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                        @endcan

                        @can("viewAny", $field)
                            <a href="{{ route('fields.index') }}"
                               class="bg-gray-200 hover:bg-gray-300 text-black font-bold py-2 px-4 rounded">
                                {{ __('common.back') }}
                            </a>
                        @endcan

                        @can("update", $field)
                            <a href="{{ route('fields.edit', $field->id) }}"
                               class="bg-blue-200 hover:bg-blue-300 text-black font-bold py-2 px-4 rounded">
                                {{ __('common.edit') }}
                            </a>
                        @endcan


                        @can("delete", $field)
                            @method('DELETE')
                            @csrf
                            <button type="submit"
                                    class="flex-shrink-0 bg-red-200 hover:bg-red-300 text-black font-bold py-2 px-4 rounded">
                                {{ __('common.delete') }}
                            </button>
                    </form>
                @endcan
            </div>
            <div class="flex flex-col">
                <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                    <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                        <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                            <table class="min-w-full divide-y divide-gray-200 w-full">
                                @foreach($display_elements as $title => $value)
                                    <tr class="border-b">
                                        <th scope="col" class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{ $title }}
                                        </th>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 bg-white divide-y divide-gray-200">

                                            {{ $value }}
                                        </td>
                                    </tr>
                                @endforeach
                                <tr class="border-b">
                                    <th scope="col"
                                        class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __("fields/show.associate_list") }}
                                    </th>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-blue-900 bg-white divide-y divide-gray-200">
                                        @if(!empty($field->listControl))
                                            <a href="{{route("lists_control.show", $field->listControl->id)}}">{{ $field->listControl->title ?? "" }}</a>
                                        @else
                                            {{ $field->listControl->title ?? ""}}
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
