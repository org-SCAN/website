<div>
    <label for="{{$form_elem}}" class="block font-medium text-md text-gray-700">{{$title}}</label>
    <!-- Add a list to select the format -->
    <select wire:model="format" class="form-select text-sm rounded shadow-sm mt-1">
        <option value="WGS84">WGS84</option>
        <option value="NAD27">NAD27</option>
    </select>
    @if($format == 'WGS84')
        <div class="flex">
            <div class="flex-auto pr-4">
                <em class="text-sm">Longitude :</em>
                <input value="{{ old($form_elem, $previous ?? "")}}" type="text" name="{{$form_elem}}" id="{{$form_elem}}"
                    class="form-input rounded-md shadow-sm mt-1 block w-full"
                    placeholder="{{$placeHolder}}"/>
            </div>
            <div class="flex-auto pr-4">
                <em class="text-sm">Latitude :</em>
                <input value="{{ old($form_elem, $previous ?? "")}}" type="text" name="{{$form_elem}}" id="{{$form_elem}}"
                    class="form-input rounded-md shadow-sm mt-1 block w-full"
                    placeholder="{{$placeHolder}}"/>
            </div>
        </div>
    @else
        <div class="flex">
            <div class="flex-auto pr-4">
                <em class="text-sm">NAD27 :</em>
                <input value="{{ old($form_elem, $previous ?? "")}}" type="text" name="{{$form_elem}}" id="{{$form_elem}}"
                    class="form-input rounded-md shadow-sm mt-1 block w-full"
                    placeholder="{{$placeHolder}}"/>
            </div>
        </div>
    @endif
    <small id="{{$form_elem}}Help" class="block font-medium text-sm text-gray-500">
        {{$hint}} <em class="text-sm text-red-600">{{$warning}}</em></small>
    @error($form_elem)
    <p class="text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>

