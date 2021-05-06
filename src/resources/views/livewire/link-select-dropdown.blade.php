<div>
    <div wire:ignore>
        <select class="form-input rounded-md shadow-sm pt-4 pl-4 block w-30" id="{{$label}}" name="{{$label}}">
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
        function insertParam(key, value) {
            key = encodeURIComponent(key);
            value = encodeURIComponent(value);

            // kvp looks like ['key1=value1', 'key2=value2', ...]
            var kvp = document.location.search.substr(1).split('&');
            let i=0;

            for(; i<kvp.length; i++){
                if (kvp[i].startsWith(key + '=')) {
                    let pair = kvp[i].split('=');
                    pair[1] = value;
                    kvp[i] = pair.join('=');
                    break;
                }
            }

            if(i >= kvp.length){
                kvp[kvp.length] = [key,value].join('=');
            }

            // can return this or...
            let params = kvp.join('&');

            // reload page with new params
            document.location.search = params;
        }

        $(document).ready(function() {
            $('#{{$label}}').select2();
            $('#{{$label}}').on('change', function (e) {
                var data = $('#{{$label}}').select2("val");
                insertParam("{{$label}}", data)
               // window.location.replace("?from="+data);
            @this.set('selected', data);
            });
        });
    </script>

@endpush
