<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Details of the user: <b> {{$user->name}}</b>
        </h2>
    </x-slot>

    <div>
        <div class="max-w-6xl mx-auto py-10 sm:px-6 lg:px-8">
            <div class="block mb-8">
                <form action="{{route('user.destroy', $user->id)}}" method="POST"
                      class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                    <a href="{{ route('user.index') }}"
                       class="bg-gray-200 hover:bg-gray-300 text-black font-bold py-2 px-4 rounded">Back to list</a>
                    <a href="{{ route('user.edit', $user->id) }}"
                       class="bg-blue-200 hover:bg-blue-300 text-black font-bold py-2 px-4 rounded">Edit</a>

                    <button type="submit"
                            class="flex-shrink-0 bg-red-200 hover:bg-red-300 text-black font-bold py-2 px-4 rounded">
                        Delete
                    </button>
                </form>
            </div>
            <div class="flex flex-col">
                <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                    <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                        <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                            <table class="min-w-full divide-y divide-gray-200 w-full">

                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="block mt-8 flex flex-col">
                <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                    <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                        <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                            <table class="min-w-full divide-y divide-gray-200 w-full">

                                <tr class="border-b">
                                    <th scope="col"
                                        class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        ID
                                    </th>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 bg-white divide-y divide-gray-200">
                                        {{ $user->id }}
                                    </td>
                                </tr>

                                <tr class="border-b">
                                    <th scope="col"
                                        class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Name
                                    </th>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 bg-white divide-y divide-gray-200">
                                        {{ $user->name }}
                                    </td>
                                </tr>

                                <tr class="border-b">
                                    <th scope="col"
                                        class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Email
                                    </th>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 bg-white divide-y divide-gray-200">
                                        {{ $user->email }}
                                    </td>
                                </tr>

                                <tr class="border-b">
                                    <th scope="col"
                                        class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Roles
                                    </th>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 bg-white divide-y divide-gray-200">
                                        @if ($role=="Administrator" || $role=="Owner")
                                            <div class="ml-2 text-sm text-gray-400">
                                                {{ $role }}
                                            </div>
                                        @else
                                        @php
                                            $usser = Auth::user();
                                            $team_id = $usser->ownedTeams;
                                        @endphp
                                        @if ($team_id[0]->id==$user->current_team_id)
                                        @php
                                        $roless=array()
                                        @endphp
                                        @foreach (array_values($roles) as $rol)
                                            @php
                                            array_push($roless,$rol->name)
                                            @endphp
                                        @endforeach
                                        {{ Form::open(array('url' => 'request/grant')) }}
                                        {{ Form::hidden('user_id', $user->id) }}
                                        {{ Form::hidden('user_current_team_id', $user->current_team_id) }}
                                            <div class="ml-2 text-sm text-gray-400 underline">
                                                {{ Form::select('role', $roless,array_search($role,$roless)) }}
                                                @php
                                                $requested_role = App\Models\RoleRequest::where('user_id',$user->id)->where('team_id',$user->current_team_id)->get();
                                                $requested_role = $requested_role->last();
                                                @endphp
                                                @if (!is_null($requested_role))
                                                @if ($requested_role->granted)
                                                    {{ __('Granted:') }}
                                                    {{ $requested_role->role }}
                                                @elseif (!$requested_role->granted)
                                                    {{ __('Requested:') }}
                                                    {{ $requested_role->role }}
                                                @endif
                                                @endif
                                                <button class="cursor-pointer ml-6 text-sm text-red-500 focus:outline-none">
                                                {{ __('Grant') }}
                                                </button>
                                            </div>
                                        {{ Form::close() }}
                                        @elseif ($team_id[0]->id!=$user->current_team_id && $user->id==$usser->id)
                                        @php
                                        $roless=array()
                                        @endphp
                                        @foreach (array_values($roles) as $rol)
                                            @php
                                            array_push($roless,$rol->name)
                                            @endphp
                                        @endforeach
                                        {{ Form::open(array('url' => 'request')) }}
                                        {{ Form::hidden('user_id', $user->id) }}
                                        {{ Form::hidden('user_current_team_id', $user->current_team_id) }}
                                            <div class="ml-2 text-sm text-gray-400 underline">
                                                {{ Form::select('role', $roless,array_search($role,$roless)) }}
                                                @php
                                                $requested_role = App\Models\RoleRequest::where('user_id',$user->id)->where('team_id',$user->current_team_id)->get();
                                                $requested_role = $requested_role->last();
                                                @endphp
                                                @if (!is_null($requested_role))
                                                @if ($requested_role->granted)
                                                    {{ __('Granted:') }}
                                                    {{ $requested_role->role }}
                                                @elseif (!$requested_role->granted)
                                                    {{ __('Requested:') }}
                                                    {{ $requested_role->role }}
                                                @endif
                                                @endif
                                                <button class="cursor-pointer ml-6 text-sm text-red-500 focus:outline-none">
                                                {{ __('Request') }}
                                                </button>
                                            </div>
                                        {{ Form::close() }}
                                        @else
                                            <div class="ml-2 text-sm text-gray-400 underline">
                                                {{ $role }}
                                            </div>
                                        @endif
                                        @endif
                                        
                                    </td>
                                </tr>
                                </tr>
                            </table>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
