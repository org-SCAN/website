<div>
    <div class="mb-8">
        <label class="inline-block w-32 font-bold">Parent list:</label>
        <select name="parent_list" class="border shadow p-2 bg-white" wire:model="selected_parent_id">
            <option value=''>Choose a parent</option>
            @foreach($parent_list as $selected_parent_id=>$value)
                <option value="{{ $selected_parent_id }}">{{ $value }}</option>
            @endforeach
        </select>
    </div>

    <div class="mb-8">
        <label class="inline-block w-32 font-bold">Child list:</label>
        <select name="childs" class="border shadow p-2 bg-white" wire:model="child_id">
            <option value=''>Choose a child</option>
            @foreach($childs as $child_id=>$value)
                <option value="{{ $child_id }}">{{ $value }}</option>
            @endforeach
        </select>
    </div>

</div>

