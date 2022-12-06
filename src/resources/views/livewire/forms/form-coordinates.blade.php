<div>
    <label for="{{$form_elem}}" class="block font-medium text-md text-gray-700">{{$title}}</label>
    <div class="flex">
        <div class="flex-auto pr-4">
            <em class="text-sm">Longitude :</em>
            <input type="text" name="{{$form_elem}}[long]" id="long"
                class="form-input rounded-md shadow-sm mt-1 block w-full"
                placeholder="{{$placeHolder}}"/>
        </div>
        <div class="flex-auto pr-4">
            <em class="text-sm">Latitude :</em>
            <input type="text" name="{{$form_elem}}[lat]" id="lat"
                class="form-input rounded-md shadow-sm mt-1 block w-full"
                placeholder="{{$placeHolder}}"/>
        </div>
    </div>
    <small id="{{$form_elem}}Help" class="block font-medium text-sm text-gray-500">
        {{$hint}} <em class="text-sm text-red-600">{{$warning}}</em></small>
    @error($form_elem)
    <p class="text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>

