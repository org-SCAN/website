<div>
    <label for="{{$form_elem}}" class="block font-medium text-md text-gray-700">{{$title}}</label>
    <div class="flex">
        <div class="flex-auto pr-4">
            <em class="text-sm">{{ __("livewire/forms/form-coordinates.latitude") }}</em>
            <input type="text" name="{{$form_elem}}[lat]" id="lat" value="{{(old($form_elem, $previous ?? ""))['lat'] ?? ""}}"
                class="form-input rounded-md shadow-sm mt-1 block w-full"
                placeholder="{{$placeHolder}}"/>
        </div>
        <div class="flex-auto pr-4">
            <em class="text-sm">{{ __("livewire/forms/form-coordinates.longitude") }}</em>
            <input type="text" name="{{$form_elem}}[long]" id="long" value="{{(old($form_elem, $previous ?? ""))['long'] ?? ""}}"
                class="form-input rounded-md shadow-sm mt-1 block w-full"
                placeholder="{{$placeHolder}}"/>
        </div>
    </div>
    <small id="{{$form_elem}}Help" class="block font-medium text-sm text-gray-500">
        {{$hint}} <em class="text-sm text-red-600">{{$warning}}</em></small>
    @error($form_elem.'.lat')
        <p class="text-sm text-red-600">{{ Str::replace($form_elem.'.lat', $this->title.' (latitude)', $message) }}</p>
    @enderror
    @error($form_elem.'.long')
        <p class="text-sm text-red-600">{{ Str::replace($form_elem.'.long', $this->title.' (longitude)', $message) }}</p>
    @enderror
</div>

