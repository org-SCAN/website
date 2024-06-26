<div>
    <label for="{{ $form_elem }}" class="block font-medium text-md text-gray-700">{{ $title }}</label>
    @for($row_index = 0; $row_index < $fieldCount; $row_index++)
        <br>
        <div wire:key="div-{{ $row_index }}" class="mt-4">
            @for($column_index = 0; $column_index < 4; $column_index++)
                <div class="flex">
                    <div class="flex-auto pr-4">
                        <em class="text-sm">Latitude :</em>
                        <input type="text" name="{{ $form_elem }}[polygons][{{ $row_index }}][{{ $column_index }}][lat]" id="lat{{ $row_index }}{{ $column_index }}"
                               class="form-input rounded-md shadow-sm mt-1 block w-full"
                               placeholder="{{ $placeHolder }}"/>
                    </div>
                    <div class="flex-auto pr-4">
                        <em class="text-sm">Longitude :</em>
                        <input type="text" name="{{ $form_elem }}[polygons][{{ $row_index }}][{{ $column_index }}][long]" id="long{{ $row_index }}{{ $column_index }}"
                               class="form-input rounded-md shadow-sm mt-1 block w-full"
                               placeholder="{{ $placeHolder }}"/>
                    </div>
                </div>
            @endfor
        </div>
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
        @if($fieldCount > 0)
            <x-jet-button type="button"
                          wire:click="removeField"
                          class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray disabled:opacity-25 transition ease-in-out duration-150">
                {{ __('place/create.fields.remove_polygon') }}
            </x-jet-button>
        @endif
    </div>
</div>


