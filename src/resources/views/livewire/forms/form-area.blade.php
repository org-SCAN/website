<div>
    <label for="{{ $form_elem }}" class="block font-medium text-md text-gray-700">{{ $title }}</label>
    @for($row_index = 0; $row_index < $fieldCount; $row_index++)
        <br>
        <jet-div wire:key="div-{{ $row_index }}" class="mt-4">
            @for($column_index = 0; $column_index < 4; $column_index++)
                <div class="flex">
                    <div class="flex-auto pr-4">
                        <em class="text-sm"><em class="text-sm">{{ __("livewire/forms/form-area.latitude") }}</em></em>
                        <input type="text" name="{{ $form_elem }}[polygons][{{ $row_index }}][{{ $column_index }}][lat]"
                               id="lat{{ $row_index }}{{ $column_index }}"
                               class="form-input rounded-md shadow-sm mt-1 block w-full"
                               value="{{ old($form_elem.'.polygons.'.$row_index.'.'.$column_index.'.long',
                                        $previous['polygons'][$row_index][$column_index]['lat'] ?? '') }}"
                               placeholder="{{ $placeHolder }}"/>
                    </div>
                    <div class="flex-auto pr-4">
                        <em class="text-sm"><em class="text-sm">{{ __("livewire/forms/form-area.longitude") }}</em></em>
                        <input type="text"
                               name="{{ $form_elem }}[polygons][{{ $row_index }}][{{ $column_index }}][long]"
                               id="long{{ $row_index }}{{ $column_index }}"
                               class="form-input rounded-md shadow-sm mt-1 block w-full"
                               value="{{ old($form_elem.'.polygons.'.$row_index.'.'.$column_index.'.long',
                                        $previous['polygons'][$row_index][$column_index]['long'] ?? '') }}"
                               placeholder="{{ $placeHolder }}"/>
                    </div>
                </div>
            @endfor
            @error($form_elem.'.polygons.*.*.lat')
            <p class="text-sm text-red-600">{{ Str::replace($form_elem.'.polygons.0.0.lat', $this->title.' (latitudes)', $message) }}</p>
            @enderror
            @error($form_elem.'.polygons.*.*.long')
            <p class="text-sm text-red-600">{{ Str::replace($form_elem.'.polygons.0.0.long', $this->title.' (longitudes)', $message) }}</p>
            @enderror
        </jet-div>
    @endfor

    <small id="{{ $form_elem }}Help" class="block font-medium text-sm text-gray-500">
        {{ $hint }} <em class="text-sm text-red-600">{{ $warning }}</em>
    </small>

    <div class="flex items-center justify-end px-4 py-3 text-right sm:px-6">
        <x-jet-button type="button"
                      wire:click="addField"
                      class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray disabled:opacity-25 transition ease-in-out duration-150">
            {{ __('place/create.fields.add_polygon') }}
        </x-jet-button>
        @if($fieldCount > 1)
            <x-jet-button type="button"
                          wire:click="removeField"
                          class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray disabled:opacity-25 transition ease-in-out duration-150">
                {{ __('place/create.fields.remove_polygon') }}
            </x-jet-button>
        @endif
    </div>
</div>


