<div>
    <label for="{{ $form_elem }}" class="block font-medium text-md text-gray-700">
        {!! $title !!}
    </label>
    @if($rangeable)
        <div class="row">
            <div class="col-4">
                <label for="{{ $form_elem }}" class="block font-medium text-md text-gray-700">
                    Min :
                </label>
                <input value="{{ (old($form_elem, $previous ?? ""))['min'] ?? ""}}" type="number" name="{{ $form_elem }}[min]" id="{{ $form_elem }}-min"
                       class="form-input rounded-md shadow-sm mt-1 block w-full"
                       placeholder="{{ $placeHolder }}"/>
            </div>
            <div class="col-4">
                @endif
                <label for="{{ $form_elem }}" class="block font-medium text-md text-gray-700">
                    Current :
                </label>
                <input value="{{ $rangeable ? (old($form_elem, $previous ?? ""))['current'] ?? "" : old($form_elem, $previous ?? "") }}" type="number" name="{{ $form_elem }}{{ $rangeable ? '[current]' : '' }}" id="{{$form_elem }}"
                       class="form-input rounded-md shadow-sm mt-1 block w-full"
                       placeholder="{{ $placeHolder }}"/>

                @if($rangeable)
            </div>
            <div class="col-4">
                <label for="{{ $form_elem }}" class="block font-medium text-md text-gray-700">
                    Max :
                </label>
                <input value="{{ (old($form_elem, $previous ?? ""))['max'] ?? "" }}" type="number" name="{{ $form_elem }}[max]" id="{{ $form_elem }}-max"
                       class="form-input rounded-md shadow-sm mt-1 block w-full"
                       placeholder="{{ $placeHolder }}"/>
            </div>
        </div>
    @endif
    <small id="{{ $form_elem }}Help" class="block font-medium text-sm text-gray-500">
        {!! $hint !!} <em class="text-sm text-red-600">{!! $warning !!}</em></small>

    @if($showError)
        @error($form_elem)
        <p class="text-sm text-red-600">{{ $message }}</p>
        @enderror
    @endif
</div>
