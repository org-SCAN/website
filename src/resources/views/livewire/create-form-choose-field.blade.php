<div>
    <div>
        <label for="{{ $form_elem }}" class="block font-medium text-md text-gray-700"> {!! $title !!}</label>
        <select wire:model="selectedField" class="form-input rounded-md shadow-sm mt-1 block w-full" id="{{ $form_elem }}" name="{{ $form_elem }}">
            <option value="">{{ $placeHolder }}</option>
            @foreach($associated_list as $key => $value)
                <option value="{{ $key }}" @selected($previous == $key)}>{{ $value }}</option>
            @endforeach
        </select>
        <small id="{{ $form_elem }}Help" class="block font-medium text-sm text-gray-500">
            {!! $hint !!} <em class="text-sm text-red-600">{!! $warning !!}</em></small>
        @if($showError)
            @error($form_elem)
            <p class="text-sm text-red-600">{{ $message }}</p>
            @enderror
        @endif
    </div>

        @if($this->rangeable)
            <div class="py-4 bg-white sm:p-6">
                @livewire('forms.form', [
                    'form_elem' => 'range',
                    'type' => 'checkbox',
                    'title' => 'Add a range to this field ?',
                    'hint' => 'If checked, you will be able to define a range for this field. Example : Age => Min : 18, Current : 20, Max : 25',
                ])
            </div>
        @endif

        @if($this->isList)
        <!--  Linked List SECTION  -->

        <div class="px-4 py-4 bg-white sm:p-6">
            @php($form_elem = "linked_list")
            @livewire('forms.form', [
                'form_elem' => $form_elem,
                'type' => 'select-dropdown',
                'title' => 'Field\'s associated list',
                'help' => 'Define a list which is associated with this field.',
                'associated_list' => \App\Models\ListControl::where('visible', true)->orderBy('title')->get()->pluck('title', 'id'),
                'previous' => old($form_elem),
                'placeHolder' => '-- Select a list --',
            ])
            @stack('scripts')
        </div>

        @endif
</div>