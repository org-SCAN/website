@section('title',"Edit ".$field->title." field")
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Edit : <b>{{$field->title}}</b>
        </h2>
    </x-slot>

    <div>
        <div class="max-w-4xl mx-auto py-10 sm:px-6 lg:px-8">
            <div class="block mb-8">
                <a href="{{ route('fields.index')  }}" class="bg-gray-200 hover:bg-gray-300 text-black font-bold py-2 px-4 rounded">Back</a>
            </div>
            <div class="mt-5 md:mt-0 md:col-span-2">
                <form method="post" action="{{ route('fields.update', $field) }}">
                    @csrf
                    @method('PUT')
                    <div class="shadow overflow-hidden sm:rounded-md">
                        <!--  TITLE SECTION  -->
                        <div class="px-4 py-5 bg-white sm:p-6">
                            @php($form_elem = "title")
                            @livewire("forms.form", [
                                'form_elem' => $form_elem,
                                'type' => "text",
                                'title' => "Field's title",
                                'placeHolder' => "Example : Full Name",
                                'hint' => "It'll be shown as title when the field is used.",
                                'previous' => $field->{$form_elem}])
                        </div>

                        <!--  PLACEHOLDER SECTION  -->

                        <div class="px-4 py-5 bg-white sm:p-6">

                            @php($form_elem = "placeholder")
                            @livewire("forms.form", [
                                'form_elem' => $form_elem,
                                'type' => "text",
                                'title' => "Field's placeholder",
                                'placeHolder' => "The placehold is shown as an example when the field is asked (just like this)",
                                'hint' => "It'll be shown as an example when the field is asked.",
                                'previous' => $field->{$form_elem}])
                        </div>

                        <!--  REQUIRED SECTION  -->

                        <div class="px-4 py-5 bg-white sm:p-6">
                            @php($form_elem = "required")
                            <label for="{{$form_elem}}" class="block font-medium text-md text-gray-700">Field's
                                requirement state</label>
                            @if($field->required == "Required")
                                <b>This field is currently required</b>
                            @endif
                            @php( $list = $lists["required"])
                            <x-form-select name="{{$form_elem}}" :options="$list" id="{{$form_elem}}"
                                           class="form-input rounded-md shadow-sm mt-1 block w-full"/>
                            <small id="{{$form_elem}}Help" class="block font-medium text-sm text-gray-500 ">Define the
                                field's requirement state.</small>

                            @error($form_elem)
                            <p class="text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!--  STATUS SECTION  -->

                        <div class="px-4 py-5 bg-white sm:p-6">
                            @php($form_elem = "status")
                            <label for="{{$form_elem}}" class="block font-medium text-md text-gray-700">Field's activation status </label>

                            @php( $list = $lists["status"])
                            <x-form-select name="{{$form_elem}}" :options="$list" id="{{$form_elem}}" class="form-input rounded-md shadow-sm mt-1 block w-full"/>
                            <small id="{{$form_elem}}Help" class="block font-medium text-sm text-gray-500 ">Define where the field will be deployed.</small>

                            @error($form_elem)
                            <p class="text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!--  ORDER SECTION  -->

                        <div class="px-4 py-5 bg-white sm:p-6">

                            @php($form_elem = "order")
                            @livewire("forms.form", [
                                'form_elem' => $form_elem,
                                'type' => "number",
                                'title' => "Field's order",
                                'placeHolder' => "Example : 3",
                                'hint' => "It'll be used to order the field. Fields are first order by requirement state, then by order",
                                'previous' => $field->{$form_elem}])
                        </div>

                        <!--  DESCRIPTIVE_VALUE SECTION  -->

                        <div class="px-4 py-5 bg-white sm:p-6">
                            @php($form_elem = "descriptive_value")
                            @livewire("forms.form", [
                                'form_elem' => $form_elem,
                                'type' => "checkbox",
                                'title' => "Is that a descriptive value ?",
                                'hint' => "If checked, it will be displayed in the Persons section.",
                                'previous' => $field->{$form_elem}])
                        </div>


                        <!--  BEST_DESCRIPTIVE_VALUE SECTION  -->

                        <div class="px-4 py-5 bg-white sm:p-6">

                            @php($form_elem = "best_descriptive_value")
                            @livewire("forms.form", [
                                'form_elem' => $form_elem,
                                'type' => "checkbox",
                                'title' => "Is that a descriptive value ?",
                                'hint' => "If checked, it will be displayed in the Persons section.",
                                'previous' => $field->{$form_elem}])
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

                        <!--  Linked List SECTION  -->

                        <div class="px-4 py-5 bg-white sm:p-6">
                            @php($form_elem = "linked_list")
                            <label for="{{$form_elem}}" class="block font-medium text-md text-gray-700">Field's
                                associated list
                            </label>

                            @php( $list = $lists["linked_list"])
                            <x-form-select name="{{$form_elem}}" :options="$list" id="{{$form_elem}}"
                                           class="form-input rounded-md shadow-sm mt-1 block w-full"/>
                            <small id="{{$form_elem}}Help" class="block font-medium text-sm text-gray-500 ">Define a
                                list which is associated with this field.</small>

                            @error($form_elem)
                            <p class="text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center justify-end px-4 py-3 bg-gray-50 text-right sm:px-6">
                            <button class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray disabled:opacity-25 transition ease-in-out duration-150">
                                Save
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
