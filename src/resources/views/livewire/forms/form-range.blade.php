<div>
    <label for="{{ $form_elem }}" class="block font-medium text-md text-gray-700">
        {!! $title !!} :
    </label>
    <input value="{{ old($form_elem, $previous ?? "")}}" type="range" name="{{ $form_elem }}" id="{{ $form_elem }}"
           min="0" max="100" step=1 class=" w-3/4 h-3 bg-gray-200 rounded-lg form-range appearance-none ring-red-500" oninput="this.nextElementSibling.value = this.value"/>

    <output class="w-1/4"></output>
    <small id="{{ $form_elem }}Help" class="block font-medium text-sm text-gray-500">
        {!! $hint !!} <em class="text-sm text-red-600">{!! $warning !!}</em></small>
    @if($showError)
        @error($form_elem)
        <p class="text-sm text-red-600">{{ $message }}</p>
        @enderror
    @endif
</div>

