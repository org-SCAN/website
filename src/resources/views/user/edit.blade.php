@section('title', __('user/edit.title', ['username' => $user_found->name]))
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {!! __('user/edit.section_title', ['username' => $user_found->name]) !!}
        </h2>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css"
              integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2"
              crossorigin="anonymous">
    </x-slot>

    <div>
        <div class="max-w-4xl mx-auto py-10 sm:px-6 lg:px-8">
            <div class="block mb-8">
                <a href="{{URL::previous() }}"
                   class="bg-gray-200 hover:bg-gray-300 text-black font-bold py-2 px-4 rounded">{{ __('common.back') }}</a>
                @can("delete", $user_found)
                    <form class="inline-block" action="{{ route('user.destroy', $user_found->id) }}" method="POST"
                          onsubmit="return confirm('Are you sure you want to delete this user?');">
                        <input type="hidden" name="_method" value="DELETE">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <button type="submit"
                                class="flex-shrink-0 bg-red-200 hover:bg-red-300 text-black
                                font-bold py-2 px-4 rounded">
                            {{ __('common.delete') }}
                        </button>
                    </form>
                @endcan
            </div>

            @error('cantDeleteUser')
            <p class="text-sm text-red-600">{{ $message }}</p>
            @enderror
            <div class="mt-5 md:mt-0 md:col-span-2">
                <form method="post" action="{{ route('user.update', $user_found->id) }}">
                    @csrf
                    @method('put')
                    <div class="shadow overflow-hidden sm:rounded-md">
                        <div class="px-4 py-4 bg-white sm:p-6">
                            @php($form_elem = "name")
                            @livewire("forms.form", [
                                'form_elem' => $form_elem,
                                'type' => "text",
                                'title' => __("user/create.form.name.title"),
                                'previous' => old($form_elem, $user_found->name),
                            ])
                        </div>

                        <div class="px-4 py-4 bg-white sm:p-6">
                            @php($form_elem = "email")
                            @livewire("forms.form", [
                                'form_elem' => $form_elem,
                                'type' => "email",
                                'title' => __("user/create.form.email.title"),
                                'previous' => old($form_elem, $user_found->email),
                            ])
                        </div>

                        <div class="px-4 py-4 bg-white sm:p-6">
                            @php($form_elem = "role_id")
                            @livewire("forms.form", [
                                'form_elem' => $form_elem,
                                'type' => "select-dropdown",
                                'associated_list' => \App\Models\Role::list(),
                                'title' => __("user/create.form.role.title"),
                                'placeHolder' => __("user/create.form.role.placeholder"),
                                'previous' => old($form_elem, $user_found->role->id),
                            ])
                        </div>

                        <div class="px-4 py-4 bg-white sm:p-6">
                            @php($form_elem = "crew_id")
                            @livewire("forms.form", [
                                'form_elem' => $form_elem,
                                'type' => "select-dropdown",
                                'associated_list' => \App\Models\Crew::list(),
                                'title' => __("user/create.form.team.title"),
                                'placeHolder' => __("user/create.form.team.placeholder"),
                                'previous' => old($form_elem, $user_found->crew->id),
                            ])
                        </div>

                        <div class="flex items-center justify-end px-4 py-3 bg-gray-50 text-right sm:px-6">
                            <button
                                class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent
                                rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700
                                active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray
                                disabled:opacity-25 transition ease-in-out duration-150">
                                {{ __('common.save') }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
