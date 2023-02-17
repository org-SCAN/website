@php use App\Models\ListRelationType; @endphp
@section('title',"Add a new relation")
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Add a relation
        </h2>
    </x-slot>
    <div>
        <div class="max-w-4xl mx-auto py-10 sm:px-6 lg:px-8">
            <div class="block mb-8">
                <a href="{{ route('links.index') }}"
                   class="bg-gray-200 hover:bg-gray-300 text-black font-bold py-2 px-4 rounded">Back</a>
            </div>
            <div class="mt-5 md:mt-0 md:col-span-2">
                <form method="post" action="{{ route('links.store') }}">
                    @csrf
                    <div class="shadow overflow-hidden sm:rounded-md">

                        <!--  from SECTION  -->
                        <div class="px-4 py-5 bg-white sm:p-6">

                            @php($form_elem = "from")

                            @livewire("forms.form", [
                                'form_elem' => 'from',
                                'type' => 'select-dropdown',
                                'title' => 'Person 1',
                                'placeHolder' => '-- Select the first person --',
                                'associated_list' => $lists["refugees"],
                                'previous' => old($form_elem, $selected_value = (!empty($refugee) && !empty($origin) && $origin == "from") ? $refugee->id : $form_elem),
                            ])

                            @if(auth()->user()->crew->hasEvent())
                                <input type="checkbox" name="everyoneFrom" @checked(old("everyoneFrom")) value="1"> From
                                all
                                person registered in the same event
                                @error("everyoneFrom")
                                <p class="text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            @endif
                        </div>

                        <div class="row pl-4 pr-4 bg-white">
                            <div class="col-8 bg-white sm:p-6">
                                <!--  Relation SECTION  -->

                                @php($form_elem = "relation_id")
                                <label for="{{$form_elem}}"
                                       class="block font-medium text-md text-gray-700">Relation</label>

                                @php( $list = $lists["relations"])
                                @livewire("select-dropdown", ['label' => $form_elem, 'placeholder' => '-- Select the
                                relation --', 'datas' => $list, 'selected_value' => old($form_elem, $form_elem)])
                                @stack('scripts')

                                @error($form_elem)
                                <p class="text-sm text-red-600">{{ $message }}</p>
                                @enderror

                            </div>
                            <div class="col-4 bg-white sm:p-6">

                                <!--  Relation SECTION  -->

                                @php($form_elem = "type")
                                <label for="{{$form_elem}}" class="block font-medium text-md text-gray-700">Type</label>

                                @php( $list = ListRelationType::list())
                                @livewire("select-dropdown", ['label' => $form_elem, 'placeholder' => '-- Select the
                                type --', 'datas' => $list, 'selected_value' => old($form_elem, $form_elem)])
                                @stack('scripts')

                                @error($form_elem)
                                <p class="text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!--  To SECTION  -->
                        <div class="px-4 py-5 bg-white sm:p-6">
                            @php($form_elem = "to")

                            @livewire("forms.form", [
                                'form_elem' => 'to',
                                'type' => 'select-dropdown',
                                'title' => 'Person 2',
                                'placeHolder' => '-- Select the second person --',
                                'associated_list' => $lists["refugees"],
                                'previous' => old($form_elem, (!empty($refugee) && !empty($origin) && $origin == "to") ? $refugee->id : $form_elem ),
                            ])

                            @if(auth()->user()->crew->hasEvent())
                                <input type="checkbox" name="everyoneTo" @checked(old("everyoneTo")) value="1"> To all
                                person registered in the same event
                                @error("everyoneTo")
                                <p class="text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            @endif
                        </div>

                        <!-- Date SECTION -->
                        <div class="px-4 py-5 bg-white sm:p-6">
                            @livewire("forms.form", [
                            'form_elem' => 'date',
                            'type' => "date",
                            'title' => "Date"])
                        </div>

                        <!--  detail SECTION  -->
                        <div class="px-4 py-5 bg-white sm:p-6">
                            @livewire("forms.form", [
                            'form_elem' => 'detail',
                            'type' => "text",
                            'title' => "Detail",
                            'placeHolder' => "Father"])
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
<script>
    // on change of the relation, set the associated type
    $('#relation_id').change(function () {
        var relations_type = [];
        @foreach(App\Models\ListRelation::all()->pluck('relation_type_id', 'id') as $relation => $type)
            relations_type["{{ $relation }}"] = "{{ $type }}";
        @endforeach
        var relation_id = $("#relation_id").val();
        $("#type")
            .val(relations_type[relation_id])
            .trigger("change");

    });
</script>
