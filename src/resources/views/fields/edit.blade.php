@php use App\Models\Field; @endphp
@php use App\Models\ListControl; @endphp
@section('title', __('fields/edit.edit_field', ['field_title' => $field->title]))
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('fields/edit.edit') }} : <strong>{{$field->title}}</strong>
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
                        <div class="px-4 py-4 bg-white sm:p-6">
                            @php($form_elem = "title")
                            @livewire("forms.form", [
                                'form_elem' => $form_elem,
                                'type' => "text",
                                'title' => __('fields/create.field_title'),
                                'placeHolder' => __('fields/create.field_title_placeholder'),
                                'hint' => __('fields/create.field_title_hint'),
                                'previous' => $field->{$form_elem}
                            ])
                        </div>

                        <!--  PLACEHOLDER SECTION  -->

                        <div class="px-4 py-4 bg-white sm:p-6">

                            @php($form_elem = "placeholder")
                            @livewire("forms.form", [
                                'form_elem' => $form_elem,
                                'type' => "text",
                                'title' => __('fields/create.field_placeholder'),
                                'placeHolder' => __('fields/create.field_placeholder_placeholder'),
                                'hint' => __('fields/create.field_placeholder_hint'),
                                'previous' => $field->{$form_elem}
                            ])
                        </div>

                        @if($field->dataType->rangeable && false)
                            <div class="py-4 bg-white sm:p-6">
                                @livewire('forms.form', [
                                'form_elem' => 'range',
                                'type' => 'checkbox',
                                'title' => __('fields/edit.field_range_title'),
                                'hint' => __('fields/edit.field_range_hint'),
                                'previous' => $field->range
                                ])
                            </div>
                        @endif

                        <!--  IMPORTANCE  -->

                        <div class="px-4 py-4 bg-white sm:p-6">

                            @php($form_elem = "importance")
                            @livewire("forms.form", [
                            'form_elem' => $form_elem,
                            'type' => "range",
                            'title' => __('fields/create.field_importance'),
                            'hint' => __('fields/create.field_importance_hint'),
                            'previous' => $field->{$form_elem}])
                        </div>

                        <!--  REQUIRED SECTION  -->

                        <div class="px-4 py-4 bg-white sm:p-6">
                            @php($form_elem = "required")
                            @livewire("forms.form", [
                           'form_elem' => $form_elem,
                           'type' => "select-dropdown",
                           'associated_list' => Field::$requiredTypes,
                           'title' => __('fields/create.field_required'),
                           'hint' => __('fields/create.field_required_hint'),
                           'placeHolder' => __('fields/create.field_required_placeholder'),
                           'warning' => __('fields/create.field_required_warning'),
                           'previous' => array_search($field->{$form_elem}, Field::$requiredTypes),
                            ])

                            @if($field->required == "Required")
                                <strong>{{ __("fields/edit.field_currently_required") }}</strong>
                            @endif

                        </div>

                        <!--  STATUS SECTION  -->

                        <div class="px-4 py-4 bg-white sm:p-6">
                            @php($form_elem = "status")
                            @livewire("forms.form", [
                            'form_elem' => $form_elem,
                            'type' => "select-dropdown",
                            'associated_list' => Field::$statusTypes,
                            'title' => __('fields/create.field_status'),
                            'hint' => __('fields/create.field_status_hint'),
                            'placeHolder' => __('fields/create.field_status_placeholder'),
                            'warning' => __('fields/create.field_status_warning'),
                            'previous' => array_search($field->{$form_elem}, Field::$statusTypes),

                            ])
                        </div>

                        <!--  ORDER SECTION  -->

                        <div class="px-4 py-4 bg-white sm:p-6">

                            @php($form_elem = "order")
                            @livewire("forms.form", [
                                'form_elem' => $form_elem,
                                'type' => "number",
                                'title' => __('fields/edit.field_order'),
                                'placeHolder' => __('fields/edit.field_order_placeholder'),
                                'hint' => __('fields/edit.field_order_hint'),
                                'previous' => $field->{$form_elem}])
                        </div>

                        <!--  BEST_DESCRIPTIVE_VALUE SECTION  -->

                        <div class="px-4 py-4 bg-white sm:p-6">

                            @php($form_elem = "best_descriptive_value")
                            @livewire("forms.form", [
                            'form_elem' => $form_elem,
                            'type' => "checkbox",
                            'title' => __('fields/create.field_best_descriptive_value'),
                            'hint' => __('fields/create.field_best_descriptive_value_hint'),
                            'warning' => __('fields/create.field_best_descriptive_value_warning'),
                            'previous' => $field->{$form_elem}])
                        </div>

                        <!--  DESCRIPTIVE_VALUE SECTION  -->

                        <div class="px-4 py-4 bg-white sm:p-6">
                            @php($form_elem = "descriptive_value")
                            @livewire("forms.form", [
                            'form_elem' => $form_elem,
                            'type' => "checkbox",
                            'title' => __('fields/create.field_descriptive_value'),
                            'hint' => __('fields/create.field_descriptive_value_hint'),
                            'previous' => $field->{$form_elem}])
                        </div>

                        <div class="px-4 py-4 bg-white sm:p-6">
                            @livewire("forms.form", [
                            'form_elem' => "validation_rules",
                            'type' => "text",
                            'title' => __('fields/create.field_validation_rules'),
                            'placeHolder' => __('fields/create.field_validation_rules_placeholder'),
                            'hint' => __('fields/create.field_validation_rules_hint'),
                            'warning' => __('fields/create.field_validation_rules_warning'),
                            'previous' => $field->validation_laravel
                            ])

                        </div>

                        <div class="flex items-center justify-end px-4 py-3 bg-gray-50 text-right sm:px-6">
                            <button class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray disabled:opacity-25 transition ease-in-out duration-150">
                                {{ __('fields/edit.save') }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
