<span>
    @if($field->range)
        <div class="row">
            <div class="col-3">
                <span class="text-gray-500">
                    Min : {{ $field->getValue()['min'] }}
                </span>
            </div>
            <div class="col-3">
                <span class="">
                    Current : {{ $field->getValue()['current'] }}
                </span>
            </div>
            <div class="col-3">
                <span class="text-gray-500">
                    Max : {{ $field->getValue()['max'] }}
                </span>
            </div>

        </div>
    @else
        {{ $field->getValue() }}

    @endif
</span>