<div>
    <label for="{{$form_elem}}" class="block font-medium text-md text-gray-700">
        {{$title}}</label>
    <input value="1" type="checkbox" name="{{$form_elem}}" id="{{$form_elem}}"
        class="form-input rounded-md shadow-sm m-1 block" @checked(old($form_elem, $previous) == 1)/>
    <small id="{{$form_elem}}Help" class="block font-medium text-sm text-gray-500 ">
        {{$hint}} <em class="text-sm text-red-600">{{$warning}}</em></small>
    @error($form_elem)
    <p class="text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>
