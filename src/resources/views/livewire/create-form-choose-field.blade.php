<div>
        @livewire('forms.form', [
            'form_elem' => $form_elem,
            'type' => 'select-dropdown',
            'title' => $title,
            'hint' => $hint,
            'warning' => $warning,
            'associated_list' => $associated_list,
            'placeHolder' => $placeHolder
        ])
    @if($this->rangeable)
        @livewire('forms.form', [
            'form_elem' => 'range',
            'type' => 'checkbox',
            'title' => 'Add a range to this field ?',
            'hint' => 'If checked, you will be able to define a range for this field. Example : Age => Min : 18, Current : 20, Max : 25',
        ])



        <label for="{{$form_elem}}" class="block font-medium text-md text-gray-700"</label>

        @php( $list = $data_types )
        <x-form-select wire:model="create-form-additional-field-informations" wire:change="isRangeable" name="{{$form_elem}}" :options="$list" id="{{$form_elem}}"
                       class="form-input rounded-md shadow-sm mt-1 block w-full"/>
        <small id="{{$form_elem}}Help" class="block font-medium text-sm text-gray-500 ">It'll be
            used to store the datas. <em class="text-sm text-red-600"> Be careful : you couldn't
                change this value later</em></small>

        @error($form_elem)
        <p class="text-sm text-red-600">{{ $message }}</p>
        @enderror
    @endif
</div>
