@section('title',"Edit ".$user_found->name."'s details")
<x-app-layout >
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Edit : <b>{{$user_found->name}}</b>
        </h2>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css"
              integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2"
              crossorigin="anonymous">
    </x-slot>

    <div>
        <div class="max-w-4xl mx-auto py-10 sm:px-6 lg:px-8">
            <div class="block mb-8">
                @can("viewAny", $user_found)
                    <a href="{{ route('user.index') }}"
                       class="bg-gray-300 hover:bg-gray-400 text-black font-bold py-2 px-4 rounded">Back to list</a>
                @endcan
                <a href="{{URL::previous() }}"
                   class="bg-gray-200 hover:bg-gray-300 text-black font-bold py-2 px-4 rounded">Back</a>
                @can("delete", $user_found)
                    <form class="inline-block" action="{{ route('user.destroy', $user_found->id) }}" method="POST"
                          onsubmit="return confirm('Are you sure you want to delete this user?');">
                        <input type="hidden" name="_method" value="DELETE">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <button type="submit"
                                class="flex-shrink-0 bg-red-200 hover:bg-red-300 text-black font-bold py-2 px-4 rounded">
                            Delete
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
                        <div class="px-4 py-5 bg-white sm:p-6">
                            <label for="name" class="block font-medium text-sm text-gray-700">Name</label>
                            <input type="text" name="name" id="name"
                                   class="form-input rounded-md shadow-sm mt-1 block w-full"
                                   value="{{ old('name', $user_found->name) }}"/>
                            @error('name')
                            <p class="text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="px-4 py-5 bg-white sm:p-6">
                            <label for="email" class="block font-medium text-sm text-gray-700">Email</label>
                            <input type="email" name="email" id="email"
                                   class="form-input rounded-md shadow-sm mt-1 block w-full"
                                   value="{{ old('email', $user_found->email) }}"
                                   disabled/>
                            @error('email')
                            <p class="text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="px-4 py-5 bg-white sm:p-6">
                            <label for="role_id" class="block font-medium text-sm text-gray-700">Role</label>

                            <div class="switch-toggle switch-3 switch-candy">
                                @livewire("select-dropdown", ['label' => 'role_id', 'placeholder' => "-- Select the role
                                --", 'datas' =>
                                array_column(\App\Models\Role::all()->toArray() , 'name',
                                'id'), "selected_value"=>$user_found->role->id])
                                @stack('scripts')
                            </div>
                            @error('role_id')
                            <p class="text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="px-4 py-5 bg-white sm:p-6">
                            <label for="crew_id" class="block font-medium text-sm text-gray-700">Team</label>

                            <div class="switch-toggle switch-3 switch-candy">
                                @livewire("select-dropdown", ['label' => 'crew_id', 'placeholder' => "-- Select the team
                                --", 'datas' =>
                                array_column(\App\Models\Crew::all()->toArray() , 'name', 'id'),
                                "selected_value"=>$user_found->crew->id])
                                @stack('scripts')
                            </div>
                        </div>

                        <div class="flex items-center justify-end px-4 py-3 bg-gray-50 text-right sm:px-6">
                            <button
                                class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray disabled:opacity-25 transition ease-in-out duration-150">
                                Edit
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
