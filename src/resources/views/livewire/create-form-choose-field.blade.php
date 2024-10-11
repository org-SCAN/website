<div>
    <div>
        <label for="{{ $form_elem }}" class="block font-medium text-md text-gray-700"> {!! $title !!}</label>
        <select wire:model.live="selectedField" class="form-input rounded-md shadow-sm mt-1 block w-full" id="{{ $form_elem }}" name="{{ $form_elem }}">
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
                    'title' => __("livewire/create-form-choose-field.rangeable.title"),
                    'hint' => __("livewire/create-form-choose-field.rangeable.hint"),
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
                'title' => __("livewire/create-form-choose-field.associated_list.title"),
                'hint' => __("livewire/create-form-choose-field.associated_list.hint"),
                'associated_list' => \App\Models\ListControl::where('visible', true)->orderBy('title')->get()->pluck('title', 'id'),
                'previous' => old($form_elem),
                'placeHolder' => __("livewire/create-form-choose-field.associated_list.placeholder"),
            ])
            @stack('scripts')
        </div>

        @endif
</div>