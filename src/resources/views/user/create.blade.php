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
                   class="bg-gray-300 hover:bg-gray-400 text-black font-bold py-2 px-4 rounded">Back to list</a>
            </div>
            <div class="mt-5 md:mt-0 md:col-span-2">
                <form method="post" action="{{ route('user.store') }}">
                    @csrf
                    <div class="shadow overflow-hidden sm:rounded-md">
                        <div class="px-4 py-5 bg-white sm:p-6">
                            <label for="name" class="block font-medium text-sm text-gray-700">Name</label>
                            <input type="text" name="name" id="name"
                                   class="form-input rounded-md shadow-sm mt-1 block w-full"
                                   value="{{ old('name', '') }}"/>
                            @error('name')
                            <p class="text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="px-4 py-5 bg-white sm:p-6">
                            <label for="email" class="block font-medium text-sm text-gray-700">Email</label>
                            <input type="email" name="email" id="email"
                                   class="form-input rounded-md shadow-sm mt-1 block w-full"
                                   value="{{ old('email', '') }}"/>
                            @error('email')
                            <p class="text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        @can('invite', \App\Models\User::class)
                            @php($form_elem = "invite")
                            <div class="px-4 py-5 bg-white sm:p-6">
                                <label for="{{ $form_elem }}" class="block font-medium text-sm text-gray-700">Send
                                    invitation email ?</label>
                                <input type="checkbox" name="{{ $form_elem }}" id="{{ $form_elem }}"
                                       class="form-input rounded-md shadow-sm mt-1 block"
                                       value=1 @checked(old($form_elem, ''))/>
                                <small id="Help_{{ $form_elem }}" class="block font-medium text-sm text-gray-700 ">
                                    If checked, you won't have to create the password. The application will send an
                                    invitation to the given email. The user will have to choose his own
                                    password.</small>
                                @error($form_elem)
                                <p class="text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        @endcan

                        <div class="px-4 py-5 bg-white sm:p-6" id="password_section">
                            <label for="password" class="block font-medium text-sm text-gray-700" id="password_label">Password</label>
                            <input type="password" name="password" id="password"
                                   class="form-input rounded-md shadow-sm mt-1 block w-full"/>
                            @error('password')
                            <p class="text-sm text-red-600">{{ $message }}</p>
                            @enderror

                            <label for="password" class="block font-medium text-sm text-gray-700"
                                   id="confirm_password_label">Confirm
                                password</label>
                            <input type="password" name="password_confirmation" id="password_confirmation"
                                   class="form-input rounded-md shadow-sm mt-1 block w-full"/>
                            <small id="Help_password" class="block font-medium text-sm text-red-500 font-bold ">
                                The password must contain at least 8 characters in length, one lowercase letter, one
                                uppercase letter, one digit </small>
                            @error('password_confirmation')
                            <p class="text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="px-4 py-5 bg-white sm:p-6">
                            @php($form_elem = "team")
                            <label for="team" class="block font-medium text-sm text-gray-700">Team</label>

                            <div class="switch-toggle switch-3 switch-candy">
                                @php($list = array_column(\App\Models\Crew::orderBy('name')->get()->toArray() , 'name', 'id'))
                                @livewire("select-dropdown", ['label' => 'team', 'placeholder' => "-- Select the team
                                --", 'datas' => $list, "selected_value"=>old('team','team')])
                                @stack('scripts')
                            </div>
                            @error($form_elem)
                            <p class="text-sm text-red-600">{{ $message }}</p>
                            @enderror

                            <small id="{{$form_elem}}Help" class="block font-medium text-sm text-gray-500 ">The role can
                                be changed later on by an admin</small>
                        </div>


                        <div class="px-4 py-5 bg-white sm:p-6">
                            @php($form_elem = "role")
                            <label for="{{$form_elem}}" class="block font-medium text-md text-gray-700">Role</label>

                            @php( $list = array_column(\App\Models\Role::orderBy('name')->get()->toArray(), 'name', 'id'))
                            <div class="switch-toggle switch-3 switch-candy">
                                @livewire("select-dropdown", ['label' => 'role', 'placeholder' => "-- Select the role
                                --", 'datas' => $list, "selected_value"=>old('role','role')])
                                @stack('scripts')
                            </div>
                            <small id="{{$form_elem}}Help" class="block font-medium text-sm text-gray-500 ">The role can
                                be changed later on by an admin</small>

                            @error($form_elem)
                            <p class="text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center justify-end px-4 py-3 bg-gray-50 text-right sm:px-6">
                            <button
                                class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray disabled:opacity-25 transition ease-in-out duration-150">
                                Create
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
