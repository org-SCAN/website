<span>
    @if($field->range)
        <div class="row">
            <div class="col-3">
                <span class="text-gray-500">
                    {{ __("livewire/forms/show/text.min") }} : {{ $field->getValue()['min'] ?? ""}}
                </span>
            </div>
            <div class="col-3">
                <span class="">
                    {{ __("livewire/forms/show/text.current") }} : {{ $field->getValue()['current'] ?? ""}}
                </span>
            </div>
            <div class="col-3">
                <span class="text-gray-500">
                    {{ __("livewire/forms/show/text.max") }} : {{ $field->getValue()['max'] ?? ""}}
                </span>
            </div>

        </div>
    @else
        {{ $field->getValue() }}
    @endif
</span>