<div>
    <label for="{{ $form_elem }}" class="block font-medium text-md text-gray-700"> {!! $title !!}</label>
        @livewire("select-dropdown", [
        'label' => $form_elem,
        'placeholder' => $placeHolder,
        'datas' => $associated_list,
        'selected_value' => old($form_elem, $previous)
        ])
    <small id="{{ $form_elem }}Help" class="block font-medium text-sm text-gray-500">
        {!! $hint !!} <em class="text-sm text-red-600">{!! $warning !!}</em></small>
    @if($showError)
        @error($form_elem)
        <p class="text-sm text-red-600">{{ $message }}</p>
        @enderror
    @endif
</div>

