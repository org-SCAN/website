<div>
    <div wire:ignore>
        <select class="form-input rounded-md shadow-sm mt-1 block w-full" id="{{$label}}" name="{{$label}}">
            <option value="">{{$placeholder}}</option>
            @foreach($datas as $key => $value)
                @php($selected = ((!empty($selected_value) && $selected_value == $key) ? "selected" : ""))
                <option value="{{ $key }}" {{$selected}}>{{ $value }}</option>
            @endforeach
        </select>
    </div>
</div>

@push('scripts')
    <script>
        $(document).ready(function() {
            function onSelectedChange(id) {
                var data = $(id).select2("val");
                @this.set('selected', data);
            }
            $('#{{$label}}').select2();
            $('#{{$label}}').change(onSelectedChange('#{{$label}}'));
        });
    </script>
@endpush
