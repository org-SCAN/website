@php use App\Models\User; @endphp
@section('title', __("user/show.title", ["username" => $user->name]))
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __("user/show.section_title") }} <strong> {{$user->name}}</strong>
        </h2>
    </x-slot>

    <div>
        <div class="max-w-6xl mx-auto py-10 sm:px-6 lg:px-8">

            @if (session()->has('inviteSuccess'))
                <div class="alert alert-success" role="alert">
                    {{ session()->get('inviteSuccess') }}
                </div>
            @endif

            <div class="block mb-8">
                <form action="{{route('user.destroy', $user->id)}}" method="POST"
                      class="w-full md:w-1/2 px-3 mb-6 md:mb-0"
                      onsubmit="return confirm({{ __("user/show.delete_confirm") }});">
                    <a href="{{ route('user.index') }}"
                       class="bg-gray-200 hover:bg-gray-300 text-black font-bold py-2 px-4 rounded">{{ __('common.back') }}</a>
                    @can('update', $user)
                        <a href="{{ route('user.edit', $user->id) }}"
                           class="bg-blue-200 hover:bg-blue-300 text-black font-bold py-2 px-4 rounded">{{ __('common.edit') }}</a>
                    @endcan
                    @can('delete', $user)
                        <input type="hidden" name="_method" value="DELETE">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <button type="submit"
                                class="flex-shrink-0 bg-red-200 hover:bg-red-300 text-black font-bold py-2 px-4 rounded">
                            {{ __('common.delete') }}
                        </button>
                    @endcan
                    @error('cantDeleteUser')
                    <p class="text-sm text-red-600">{{ $message }}</p>
                    @enderror

                </form>
                {{--Add a re-invitation option here--}}
                @can('invite', User::class)
                    <form action="{{route('user.invite', $user->id)}}" method="POST"
                          class="w-full md:w-1/2 px-3 mb-6 md:mb-0 mt-2"
                          onsubmit="return confirm({{ __("user/show.confirm_re_invite") }});">
                        @csrf
                        <button type="submit"
                                class="flex-shrink-0 bg-green-200 hover:bg-green-300 text-black font-bold py-2 px-4 rounded">
                            {{ __("user/show.re_invite") }}
                        </button>
                    </form>
                @endcan
            </div>
            <div class="block mt-8 flex flex-col">
                <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                    <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                        <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                            <table class="min-w-full divide-y divide-gray-200 w-full">
                                <caption class="sr-only">{{ __("user/show.user_details") }}</caption>
                                <tr class="border-b">
                                    <th scope="col"
                                        class="px-6 py-3 bg-gray-50 text-left text-xs font-medium
                                        text-gray-500 uppercase tracking-wider">
                                        {{ __("user/show.id") }}
                                    </th>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900
                                    bg-white divide-y divide-gray-200">
                                        {{ $user->id }}
                                    </td>
                                </tr>

                                <tr class="border-b">
                                    <th scope="col"
                                        class="px-6 py-3 bg-gray-50 text-left text-xs font-medium
                                        text-gray-500 uppercase tracking-wider">
                                        {{ __("user/show.name") }}
                                    </th>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900
                                    bg-white divide-y divide-gray-200">
                                        {{ $user->name }}
                                    </td>
                                </tr>

                                <tr class="border-b">
                                    <th scope="col"
                                        class="px-6 py-3 bg-gray-50 text-left text-xs font-medium
                                        text-gray-500 uppercase tracking-wider">
                                        {{ __("user/show.email") }}
                                    </th>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900
                                    bg-white divide-y divide-gray-200">
                                        {{ $user->email }}
                                    </td>
                                </tr>

                                <tr class="border-b">
                                    <th scope="col"
                                        class="px-6 py-3 bg-gray-50 text-left text-xs font-medium
                                        text-gray-500 uppercase tracking-wider">
                                        {{ __("user/show.roles") }}
                                    </th>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900
                                    bg-white divide-y divide-gray-200">
                                        {{$user->role->name}}
                                    </td>
                                </tr>

                                <tr class="border-b">
                                    <th scope="col"
                                        class="px-6 py-3 bg-gray-50 text-left text-xs font-medium
                                        text-gray-500 uppercase tracking-wider">
                                        {{ __("user/show.team") }}
                                    </th>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900
                                    bg-white divide-y divide-gray-200">
                                        {{$user->crew->name}}
                                    </td>
                                </tr>

                                <tr class="border-b">
                                    <th scope="col"
                                        class="px-6 py-3 bg-gray-50 text-left text-xs font-medium
                                         text-gray-500 uppercase tracking-wider">
                                        {{ __("user/show.language") }}
                                    </th>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900
                                     bg-white divide-y divide-gray-200">
                                        {{$user->language->language_name ?? ""}}
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            @can('update', $user)
                @livewire("api.api-token-manager", ["user" => $user])
            @endcan
        </div>
    </div>
</x-app-layout>
