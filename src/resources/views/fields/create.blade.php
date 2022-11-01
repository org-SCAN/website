@section('title',"Create a new field")
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Add a field
        </h2>
    </x-slot>
    <div>
        <div class="max-w-4xl mx-auto py-10 sm:px-6 lg:px-8">
            <div class="block mb-8">
                <a href="{{URL::previous() }}" class="bg-gray-200 hover:bg-gray-300 text-black font-bold py-2 px-4 rounded">Back</a>
            </div>
            <div class="mt-5 md:mt-0 md:col-span-2">
                <form method="post" action="{{ route('fields.store') }}">
                    @csrf
                    <div class="shadow overflow-hidden sm:rounded-md">

                        <!--  TITLE SECTION  -->
                        <div class="px-4 py-5 bg-white sm:p-6">
                            @livewire("form-text", [
                                'form_elem' => "title",
                                'title' => "Field's title",
                                'placeHolder' => "Example : Full Name",
                                'hint' => "It'll be shown as title when the field is used."])
                        </div>

                        <!--  PLACEHOLDER SECTION  -->

                        <div class="px-4 py-5 bg-white sm:p-6">

                            @php($form_elem = "placeholder")
                            <label for="{{$form_elem}}" class="block font-medium text-md text-gray-700">Field's
                                placeholder</label>

                            <input value="{{ old($form_elem)}}" type="text" name="{{$form_elem}}" id="{{$form_elem}}"
                                   class="form-input rounded-md shadow-sm mt-1 block w-full"
                                   placeholder="The placehold is shown as an example when the field is asked (just like this)"/>
                            <small id="{{$form_elem}}Help" class="block font-medium text-sm text-gray-500 ">It'll be
                                shown as an example when the field is asked.</small>

                            @error($form_elem)
                            <p class="text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!--  DATABASE TYPE SECTION  -->
                        <div class="px-4 py-5 bg-white sm:p-6">

                            @php($form_elem = "database_type")
                            <label for="{{$form_elem}}" class="block font-medium text-md text-gray-700">Field's Data type</label>

                            @php( $list = \App\Models\Field::$databaseTypes )
                            <x-form-select name="{{$form_elem}}" :options="$list" id="{{$form_elem}}" class="form-input rounded-md shadow-sm mt-1 block w-full" />
                            <small id="{{$form_elem}}Help" class="block font-medium text-sm text-gray-500 ">It'll be used to store the datas. <i class="text-sm text-red-600"> Be careful : you couldn't change this value later</i></small>

                            @error($form_elem)
                            <p class="text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!--  REQUIRED SECTION  -->

                        <div class="px-4 py-5 bg-white sm:p-6">
                            @php($form_elem = "required")
                            <label for="{{$form_elem}}" class="block font-medium text-md text-gray-700">Field's
                                requirement state</label>

                            @php( $list = \App\Models\Field::$requiredTypes)
                            <x-form-select name="{{$form_elem}}" :options="$list" id="{{$form_elem}}"
                                           class="form-input rounded-md shadow-sm mt-1 block w-full"/>
                            <small id="{{$form_elem}}Help" class="block font-medium text-sm text-gray-500 ">Define
                                the field's requirement state. <i class="text-sm text-red-600"> Due to deployment
                                    conditions, you can't define the field as required</i></small>

                            @error($form_elem)
                            <p class="text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!--  STATUS SECTION  -->

                        <div class="px-4 py-5 bg-white sm:p-6">
                            @php($form_elem = "status")
                            <label for="{{$form_elem}}" class="block font-medium text-md text-gray-700">Field's
                                activation status </label>

                            @php( $list = \App\Models\Field::$statusTypes)
                            <x-form-select name="{{$form_elem}}" :options="$list" id="{{$form_elem}}"
                                           class="form-input rounded-md shadow-sm mt-1 block w-full"/>
                            <small id="{{$form_elem}}Help" class="block font-medium text-sm text-gray-500 ">Define where
                                the field will be deployed. <i class="text-sm text-red-600"> Be careful, if the status
                                    is set to 'Disabled', the field won't be shown.</i></small>

                            @error($form_elem)
                            <p class="text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!--  ORDER SECTION  -->

                        <div class="px-4 py-5 bg-white sm:p-6">

                            @php($form_elem = "order")
                            <label for="{{$form_elem}}" class="block font-medium text-md text-gray-700">Field's
                                order</label>

                            <input value="{{ old($form_elem) }}" type="number" name="{{$form_elem}}" id="{{$form_elem}}"
                                   class="form-input rounded-md shadow-sm mt-1 block w-full" placeholder="Example : 3"/>
                            <small id="{{$form_elem}}Help" class="block font-medium text-sm text-gray-500 ">It'll be
                                used to order the field. Fields are first order by requirement state, then by
                                order. If you don't know the order, leave it blank.</small>

                            @error($form_elem)
                            <p class="text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>


                        <!--  DESCRIPTIVE_VALUE SECTION  -->

                        <div class="px-4 py-5 bg-white sm:p-6">

                            @php($form_elem = "descriptive_value")
                            <label for="{{$form_elem}}" class="block font-medium text-md text-gray-700">Is that a
                                descriptive value ?</label>
                            <input value="1" type="checkbox" name="{{$form_elem}}" id="{{$form_elem}}"
                                   class="form-input rounded-md shadow-sm mt-1 block" @checked(old($form_elem) == 1)/>
                            <small id="{{$form_elem}}Help" class="block font-medium text-sm text-gray-500 ">If checked,
                                it will be displayed in the Persons section. </small>
                            @error($form_elem)
                            <p class="text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>


                        <!--  BEST_DESCRIPTIVE_VALUE SECTION  -->

                        <div class="px-4 py-5 bg-white sm:p-6">

                            @php($form_elem = "best_descriptive_value")
                            <label for="{{$form_elem}}" class="block font-medium text-md text-gray-700">Is that the best
                                descriptive value ?</label>

                            <input value="1" type="checkbox" name="{{$form_elem}}" id="{{$form_elem}}"
                                   class="form-input rounded-md shadow-sm mt-1 block" @checked(old($form_elem) == 1)/>
                            <small id="{{$form_elem}}Help" class="block font-medium text-sm text-gray-500 ">If checked,
                                it will be displayed in the Manage Persons section as the main field. <i
                                    class="text-sm text-red-600"> Be careful, there is only one best descriptive value
                                    per team.</i></small>

                            @error($form_elem)
                            <p class="text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>


                        <!--  Linked List SECTION  -->

                        <div class="px-4 py-5 bg-white sm:p-6">
                            @php($form_elem = "linked_list")
                            <label for="{{$form_elem}}" class="block font-medium text-md text-gray-700">Field's
                                associated list
                                    <a href="{{route("lists_control.create")}}"
                                       class="inline-flex items-center text-blue-800">
                                        ~ Create a new list
                                    </a>
                            </label>

                            @php( $list = [" " => "Choose an associate list"]+array_column(\App\Models\ListControl::whereNotNull('displayed_value')->get()->toArray(), "title", "id"))
                            <x-form-select name="{{$form_elem}}" :options="$list" id="{{$form_elem}}"
                                           class="form-input rounded-md shadow-sm mt-1 block w-full"/>
                            <small id="{{$form_elem}}Help" class="block font-medium text-sm text-gray-500 ">Define a
                                list which is associated with this field.</small>

                            @error($form_elem)
                            <p class="text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!--

                        <div class="px-4 py-5 bg-white sm:p-6">
                            <span class="text-gray-700">Choose the fields validator options</span>
                            <div class="mt-2">
                                <div>
                                    <label class="inline-flex items-center">
                                        <input type="checkbox" class="form-checkbox">
                                        <span class="ml-2">Max : 250</span>
                                    </label>
                                </div>
                                <div>
                                    <label class="inline-flex items-center">
                                        <input type="checkbox" class="form-checkbox">
                                        <span class="ml-2">Min : 1</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        -->
                        <div class="flex items-center justify-end px-4 py-3 bg-gray-50 text-right sm:px-6">
                            <button class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray disabled:opacity-25 transition ease-in-out duration-150">
                                Add
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
