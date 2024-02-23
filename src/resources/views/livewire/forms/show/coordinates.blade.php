<span>
    <div class="row">
        <div class="col-6">
            <span>
                {{ __("livewire/forms/show/coordinates.latitude") }} : {{ $field->getValue()['lat'] ?? ""}}
            </span>
        </div>
        <div class="col-6">
            <span>
                {{ __("livewire/forms/show/coordinates.longitude") }} : {{ $field->getValue()['long'] ?? ""}}
            </span>
        </div>
    </div>
</span>
