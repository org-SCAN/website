<div>
    @foreach($fields as $index => $field)
        @if($index == array_key_last($fields))
        <div class="flex items-center justify-end px-4 py-3 bg-gray-50 text-right sm:px-6">
            <button
                class="inline-flex items-center px-4 py-2  rounded-md font-semibold hover:text-white text-red-500 tracking-widest hover:bg-red-500 focus:outline-none focus:shadow-outline-gray disabled:opacity-25 transition duration-150"
                wire:click.prevent="removeField({{ $index }})" type="button">
                <em class='fa fa-trash '
                   aria-hidden='true'></em>
            </button>
        </div>
        @endif
        <!--  Field x SECTION  -->
        <div class="px-4 py-4 bg-white sm:p-6">
            @php($form_elem = "fields[$index][name]")
            <label for="{{$form_elem}}" class="block font-medium text-md text-gray-700">Field's
                title</label>
            <input type="text" name="{{$form_elem}}" id="{{$form_elem}}"
                   class="form-input rounded-md shadow-sm mt-1 block w-full" placeholder="e.g. name"/>
            <small id="{{$form_elem}}Help" class="block font-medium text-sm text-gray-500">The name of
                the field</small>
            @error('fields')
            <p class="text-sm text-red-600">{{ $message }}</p>
            @enderror
            @error($form_elem)
            <p class="text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
        <div class="row px-4 py-4 bg-white ">
            <!--  Field x Datatype  -->
            <div class="sm:p-6 col-md-8 col-12">
                @php($form_elem = "fields[$index][data_type_id]")
                <label for="{{$form_elem}}" class="font-medium text-md text-gray-700">Field's Data
                    type</label>
                @php( $list = $this->data_types )
                <x-form-select name="{{$form_elem}}" :options="$list" id="{{$form_elem}}"
                               class="form-input rounded-md shadow-sm w-full"/>
                <small id="{{$form_elem}}Help" class="block font-medium text-sm text-gray-500 ">It'll be
                    used to store the datas. <em class="text-sm text-red-600"> Be careful : you couldn't
                        change this value later</em></small>
                @error($form_elem)
                <p class="text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <!--  Field x requirement  -->
            <div class=" sm:p-6 col-md-4 col-12 items-center flex">
                <div class="pt-md-4">
                    @php($form_elem = "fields[$index][required]")
                    <label for="{{$form_elem}}" class="font-medium text-md text-gray-700"> Is Required ? </label>
                    <input value="1" type="checkbox" name="{{$form_elem}}" id="{{$form_elem}}" class="rounded-md shadow-sm" @checked(old($form_elem) == 1)/>

                    @error($form_elem)
                    <p class="text-sm text-red-600">{{ $message }}</p>
                    @enderror

                </div>
            </div>
        </div>

        <hr>
    @endforeach
        <div class="px-4 bg-white sm:p-6">
            <button type="button" id="addRow"
                    class="inline-flex items-center px-4 py-2 bg-green-400 border border-transparent rounded-md font-semibold
                    text-xs text-white uppercase tracking-widest hover:bg-green-300 active:bg-green-900 focus:outline-none
                    focus:border-green-400 focus:shadow-outline-gray disabled:opacity-25 ease-in-out duration-150"
            wire:click.prevent="addField">
                Add more fields
            </button>
        </div>
</div>
