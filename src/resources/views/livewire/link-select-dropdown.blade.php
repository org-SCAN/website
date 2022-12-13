<div>
    <select class="form-input rounded-md shadow-sm pt-4 pl-4 block w-30" id="{{$label}}" name="{{$label}}">
        <option value="">{{$placeholder}}</option>
        @foreach($datas as $key => $value)
            @php($selected = ((!empty($selected_value) && $selected_value == $key) ? "selected" : ""))
            <option value="{{ $key }}" {{$selected}}>{{ $value }}</option>
        @endforeach
    </select>
</div>


