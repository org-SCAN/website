<span>
    <div class="row">
        <div class="col-6">
            <span>
                <!-- count number of polygons -->
                {{ __("livewire/forms/show/area.polygons") }} : {{ count($field->getValue()['polygons']) ?? ""}}
            </span>
        </div>
    </div>
</span>
