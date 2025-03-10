@section('title',"Add a new user")
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Add a user
        </h2>
    </x-slot>
    <div>
        <div class="max-w-4xl mx-auto py-10 sm:px-6 lg:px-8">
            <div class="block mb-8">
                <a href="{{ route('user.index') }}"
                   class="bg-gray-300 hover:bg-gray-400 text-black font-bold py-2 px-4 rounded">
                    {{ __('common.back') }}
                </a>
            </div>
            <div class="mt-5 md:mt-0 md:col-span-2">
                <form method="post" action="{{ route('user.store') }}">
                    @csrf
                    <div class="shadow overflow-hidden sm:rounded-md">
                        <div class="shadow overflow-hidden sm:rounded-md">
                        <div class="px-4 py-4 bg-white sm:p-6">
                            @php($form_elem = "name")
                            @livewire("forms.form", [
                                'form_elem' => $form_elem,
                                'type' => "text",
                                'title' => __("user/create.form.name.title"),
                                'previous' => old($form_elem),
                            ])
                        </div>

                        <div class="px-4 py-4 bg-white sm:p-6">
                            @php($form_elem = "email")
                            @livewire("forms.form", [
                                'form_elem' => $form_elem,
                                'type' => "email",
                                'title' => __("user/create.form.email.title"),
                                'previous' => old($form_elem),
                            ])
                        </div>

                        @can('invite', \App\Models\User::class)
                            <div class="px-4 py-4 bg-white sm:p-6">
                                @php($form_elem = "invite")
                                @livewire("forms.form", [
                                    'form_elem' => $form_elem,
                                    'type' => "checkbox",
                                    'title' => __("user/create.form.invite.title"),
                                    'hint' => __("user/create.form.invite.hint"),
                                    'previous' => old($form_elem),
                                ])
                            </div>
                        @endcan

                        <div class="px-4 py-4 bg-white sm:p-6" id="password_section">
                                @php($form_elem = "password")
                                @livewire("forms.form", [
                                    'form_elem' => $form_elem,
                                    'type' => "password",
                                    'title' => __("user/create.form.password.title"),
                                    'previous' => old($form_elem),
                                ])
                                @php($form_elem = "password_confirmation")
                                @livewire("forms.form", [
                                    'form_elem' => $form_elem,
                                    'type' => "password",
                                    'title' => __("user/create.form.password_confirmation.title"),
                                    'warning' => __("user/create.form.password_confirmation.warning"),
                                    'previous' => old($form_elem),
                                ])

                        </div>

                        <div class="px-4 py-4 bg-white sm:p-6">
                            @php($form_elem = "role")
                            @livewire("forms.form", [
                                'form_elem' => $form_elem,
                                'type' => "select-dropdown",
                                'associated_list' => \App\Models\Role::list(),
                                'title' => __("user/create.form.role.title"),
                                'placeHolder' => __("user/create.form.role.placeholder"),
                                'hint' => __("user/create.form.role.hint"),
                                'previous' => old($form_elem),
                            ])
                        </div>

                        <div class="px-4 py-4 bg-white sm:p-6">
                            @php($form_elem = "team")
                            @livewire("forms.form", [
                                'form_elem' => $form_elem,
                                'type' => "select-dropdown",
                                'associated_list' => \App\Models\Crew::list(),
                                'title' => __("user/create.form.team.title"),
                                'placeHolder' => __("user/create.form.team.placeholder"),
                                'hint' => __("user/create.form.team.hint"),
                                'previous' => old($form_elem),
                            ])
                        </div>

                        <div class="flex items-center justify-end px-4 py-3 bg-gray-50 text-right sm:px-6">
                            <button
                                class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray disabled:opacity-25 transition ease-in-out duration-150">
                                {{ __('common.save') }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        // hide the password fields if the invitation checkbox is checked
        document.getElementById('invite').addEventListener('change', function () {
            if (this.checked) {
                $('#password_section').hide();
            } else {
                $('#password_section').show();
            }
        });

        // if the invitation checkbox is checked, hide the password fields (on page load)
        if (document.getElementById('invite').checked) {
            $('#password_section').hide();
        }
    </script>
</x-app-layout>
