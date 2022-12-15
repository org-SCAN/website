@php use App\Models\Field; @endphp
@php use App\Models\ListControl; @endphp
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
                <a href="{{ route('fields.index')  }}"
                   class="bg-gray-200 hover:bg-gray-300 text-black font-bold py-2 px-4 rounded">Back</a>
            </div>
            <div class="mt-5 md:mt-0 md:col-span-2">
                <form method="post" action="{{ route('fields.store') }}">
                    @csrf
                    <div class="shadow overflow-hidden sm:rounded-md">

                        <!--  TITLE SECTION  -->
                        <div class="px-4 py-4 bg-white sm:p-6">
                            @php($form_elem = "title")
                            @livewire("forms.form", [
                            'form_elem' => $form_elem,
                            'type' => "text",
                            'title' => "Field's title",
                            'placeHolder' => "Example : Full Name",
                            'hint' => "It'll be shown as title when the field is used."])
                        </div>

                        <!--  PLACEHOLDER SECTION  -->
                        <div class="px-4 py-4 bg-white sm:p-6">
                            @php($form_elem = "placeholder")
                            @livewire("forms.form", [
                            'form_elem' => $form_elem,
                            'type' => "text",
                            'title' => "Field's placeholder",
                            'placeHolder' => "The placeholder is shown as an example when the field is asked (just like this)",
                            'hint' => "It'll be shown as an example when the field is asked."])
                        </div>

                        <!--  DATABASE TYPE SECTION  -->
                        <div class="px-4 py-4 bg-white sm:p-6">

                            @php($form_elem = "data_type_id")
                            @livewire("create-form-choose-field", [
                                'form_elem' => $form_elem,
                                'title' => "Field's Data type",
                                'hint' => "It'll be used to store the datas.",
                                "warning" => "Be careful : you couldn't change this value later",
                                "placeHolder" => "-- Select the field type --",
                                "associated_list" => $data_types,
                                "wireModel" => "isRangeable()",
                                "previous" => $form_elem
                            ])
                        </div>

                        <!--  IMPORTANCE  -->

                        <div class="px-4 py-4 bg-white sm:p-6">

                            @php($form_elem = "importance")
                            @livewire("forms.form", [
                            'form_elem' => $form_elem,
                            'type' => "range",
                            'title' => "Choose the field weight",
                            'hint' => "It will be used to compute the duplication."])
                        </div>


                        <!--  REQUIRED SECTION  -->

                        <div class="px-4 py-4 bg-white sm:p-6">
                            @php($form_elem = "required")
                            <label for="{{$form_elem}}" class="block font-medium text-md text-gray-700">Field's
                                requirement state</label>

                            @php( $list = Field::$requiredTypes)
                            <x-form-select name="{{$form_elem}}" :options="$list" id="{{$form_elem}}"
                                           class="form-input rounded-md shadow-sm mt-1 block w-full"/>
                            <small id="{{$form_elem}}Help" class="block font-medium text-sm text-gray-500 ">Define
                                the field's requirement state. <em class="text-sm text-red-600"> Due to deployment
                                    conditions, you can't define the field as required</em></small>

                            @error($form_elem)
                            <p class="text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!--  STATUS SECTION  -->

                        <div class="px-4 py-4 bg-white sm:p-6">
                            @php($form_elem = "status")
                            <label for="{{$form_elem}}" class="block font-medium text-md text-gray-700">Field's
                                activation status </label>

                            @php( $list = Field::$statusTypes)
                            <x-form-select name="{{$form_elem}}" :options="$list" id="{{$form_elem}}"
                                           class="form-input rounded-md shadow-sm mt-1 block w-full"/>
                            <small id="{{$form_elem}}Help" class="block font-medium text-sm text-gray-500 ">Define where
                                the field will be deployed. <em class="text-sm text-red-600"> Be careful, if the status
                                    is set to 'Disabled', the field won't be shown.</em></small>

                            @error($form_elem)
                            <p class="text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!--  BEST_DESCRIPTIVE_VALUE SECTION  -->

                        <div class="px-4 py-4 bg-white sm:p-6">

                            @php($form_elem = "best_descriptive_value")
                            @livewire("forms.form", [
                                'form_elem' => $form_elem,
                                'type' => "checkbox",
                                'title' => "Is that the best descriptive value ?",
                                'hint' => "If checked, it will be displayed in the Manage Persons section as the main field.",
                                'warning' => "Be careful, there is only one best descriptive value per team."])
                        </div>

                        <!--  DESCRIPTIVE_VALUE SECTION  -->
                        <div class="px-4 py-4 bg-white sm:p-6">

                            @php($form_elem = "descriptive_value")
                            @livewire("forms.form", [
                                'form_elem' => $form_elem,
                                'type' => "checkbox",
                                'title' => "Is that a descriptive value ?",
                                'hint' => "If checked, it will be displayed in the Persons section."])
                        </div>


                        <div class="px-4 py-4 bg-white sm:p-6">
                            @livewire("forms.form", [
                                'form_elem' => "validation_rules",
                                'type' => "text",
                                'title' => "Specific field's validation rules",
                                'placeHolder' => "Example : required|email",
                                'hint' => "Define the validation rules for this field. You can find the list of rules
                                <a href='https://laravel.com/docs/9.x/validation#available-validation-rules' class='text-blue-800'>here</a>",
                                'warning' => "Be careful, to use this section, you need to know the Laravel validation rules."
                            ])

                        </div>
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
