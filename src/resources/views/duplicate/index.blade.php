<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manage duplicates') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex flex-col">
                <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                    <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                        <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                            @foreach($duplicates as $reference => $duplicate)
                                <h3 class="font-semibold text-l text-gray-800 leading-tight m-3">Duplication
                                    on {{$reference}} </h3>
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Full Name
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Date
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Push informations
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Actions
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($duplicate as $user_information)
                                        @php($next_reference = \App\Models\Duplicate::nextID($user_information->unique_id))
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <a href="{{route("manage_refugees.show", $user_information->id)}}"
                                                   class="text-indigo-600 hover:text-blue-900">{{$user_information->full_name}}
                                                    <small>({{$user_information->unique_id}})</small></a>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                {{$user_information->date}}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <a href="{{route("api_logs.show", $user_information->api_log)}}"
                                                   class="text-indigo-600 hover:text-blue-900">See push context</a>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">

                                                <form method="post"
                                                      action="{{ route('manage_refugees.fix_duplicated_reference', $user_information->id) }}">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="hidden" name="unique_id" id="unique_id"
                                                           value="{{$next_reference}}">
                                                    <button type="submit"
                                                            class="flex-shrink-0 bg-gray-200 hover:bg-gray-300 text-black font-bold py-2 px-4 rounded"
                                                            title="Auto change the ID!">Assign new ref :
                                                        {{$next_reference}}</button>
                                                </form>
                                                <div class="block mb-2 mt-1">

                                                    <form
                                                        action="{{route('manage_refugees.destroy', $user_information->id)}}"
                                                        method="POST">
                                                        <a href="{{route("manage_refugees.edit", $user_information->id)}}"
                                                           class="flex-shrink-0 bg-blue-200 hover:bg-blue-300 text-black font-bold py-2 px-4 rounded mr-2">
                                                            <i class="fas fa-edit text-blue-600 hover:text-blue-900"
                                                               title="Edit this refugee!"></i>
                                                        </a>
                                                        @method('DELETE')
                                                        @csrf
                                                        <button type="submit"
                                                                class="flex-shrink-0 bg-red-200 hover:bg-red-300 text-black font-bold py-2 px-4 rounded">
                                                            <i class="fas fa-trash text-red-600 hover:text-red-900 "
                                                               title="Delete !"></i></button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                    <!-- More items... -->
                                    </tbody>
                                </table>

                        </div>
                    <!--
                        <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">

                                @php($confusion = \App\Models\Duplicate::getSimilarity($duplicate->toArray()))
                        <h3 class="font-semibold text-l text-gray-800 leading-tight m-3">Confusion matrix for {{$reference}} </h3>
                                <table class="min-w-full divide-y divide-gray-200">

                                    <thead class="bg-gray-50">
                                    <tr>
                                        <th></th>
                                        @foreach($duplicate as $user_information)
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
{{$user_information->full_name}}
                            </th>
@endforeach
                        </tr>
                        </thead>
                        <tbody >
@foreach($duplicate as $userKey => $user_information)
                        <tr class="bg-gray-50">
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
{{$user_information->full_name}}
                            </th>
                            <div class="bg-white divide-y divide-gray-200">
@foreach($duplicate as $user2Key => $user2_information)
                            <td style="background-color : {{\App\Models\Duplicate::make_color($confusion[$userKey][$user2Key])}}">{{$confusion[$userKey][$user2Key]}}</td>
                                                @endforeach
                            </div>
                        </tr>
@endforeach
                        </tbody>
                    </table>
             </div>
-->
                        @endforeach
                    </div>
                </div>
            </div>


        </div>
    </div>
</x-app-layout>
