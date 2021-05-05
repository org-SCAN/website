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
                            <input type="text" name="name" id="name" class="form-input rounded-md shadow-sm mt-1 block w-full"
                                   value="{{ old('name', '') }}" />
                            @error('name')
                            <p class="text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="px-4 py-5 bg-white sm:p-6">
                            <label for="email" class="block font-medium text-sm text-gray-700">Email</label>
                            <input type="email" name="email" id="email" class="form-input rounded-md shadow-sm mt-1 block w-full"
                                   value="{{ old('email', '') }}" />
                            @error('email')
                            <p class="text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="px-4 py-5 bg-white sm:p-6">
                            <label for="password" class="block font-medium text-sm text-gray-700">Password</label>
                            <input type="password" name="password" id="password" class="form-input rounded-md shadow-sm mt-1 block w-full" />
                            @error('password')
                            <p class="text-sm text-red-600">{{ $message }}</p>
                            @enderror

                            <label for="password" class="block font-medium text-sm text-gray-700">Confirm password</label>
                            <input type="password" name="confirm_password" id="confirm_password" class="form-input rounded-md shadow-sm mt-1 block w-full" />
                            @error('password_confirmation')
                            <p class="text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!--    -->

                        <div class="px-4 py-5 bg-white sm:p-6">
                            @php($form_elem = "role")
                            <label for="{{$form_elem}}" class="block font-medium text-md text-gray-700">Role</label>

                            @php( $list = array_column(\App\Models\UserRole::all()->toArray(), 'role', 'id'))
                            <x-form-select name="{{$form_elem}}" :options="$list"  id="{{$form_elem}}" class="form-input rounded-md shadow-sm mt-1 block w-full"/>
                            <small id="{{$form_elem}}Help" class="block font-medium text-sm text-gray-500 ">The role can be changed later on by an admin</small>

                            @error($form_elem)
                            <p class="text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center justify-end px-4 py-3 bg-gray-50 text-right sm:px-6">
                            <button class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray disabled:opacity-25 transition ease-in-out duration-150">
                                Create
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
