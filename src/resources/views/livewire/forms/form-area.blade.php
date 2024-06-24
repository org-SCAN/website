<div>
    <label for="{{ $form_elem }}" class="block font-medium text-md text-gray-700">{{ $title }}</label>
    @foreach($divs as $index => $div)
        <br>
        <jet-div wire:key="div-{{ $index }}" class="mt-4">

            @for($i = 0; $i < 4; $i++)
                <div class="flex">
                    <div class="flex-auto pr-4">
                        <em class="text-sm">Latitude :</em>
                        <input type="text" name="{{ $form_elem }}[polygons][{{ $div }}][{{ $i }}][lat]" id="lat{{ $div }}{{ $i }}"
                               class="form-input rounded-md shadow-sm mt-1 block w-full"
                               value="{{(old($form_elem, $previous ?? ""))['lat'] ?? ""}}"
                               placeholder="{{ $placeHolder }}"/>
                    </div>
                    <div class="flex-auto pr-4">
                        <em class="text-sm">Longitude :</em>
                        <input type="text" name="{{ $form_elem }}[polygons][{{ $div }}][{{ $i }}][long]" id="long{{ $div }}{{ $i }}"
                               class="form-input rounded-md shadow-sm mt-1 block w-full"
                               value="{{(old($form_elem, $previous ?? ""))['lat'] ?? ""}}"
                               placeholder="{{ $placeHolder }}"/>
                    </div>
                </div>
            @endfor

            @error($form_elem . '.polygons')
            <p class="text-sm text-red-600">{{ Str::replace($form_elem . '.polygons', $this->title . ' (polygons)', $message) }}</p>
            @enderror
        </jet-div>
    @endforeach

    <small id="{{ $form_elem }}Help" class="block font-medium text-sm text-gray-500">
        {{ $hint }} <em class="text-sm text-red-600">{{ $warning }}</em>
    </small>

    <div class="flex items-center justify-end px-4 py-3 text-right sm:px-6">
        <x-jet-button type="button"
                      wire:click="addField"
                      class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray disabled:opacity-25 transition ease-in-out duration-150">
            {{ __('place/create.fields.add_polygon') }}
        </x-jet-button>
    </div>
</div>
