<tbody class="bg-white divide-y divide-gray-200"  wire:sortable="updateOrder">

@foreach($fields as $field)
    <tr wire:sortable.item="{{ $field->id }}" wire:key="field-{{ $field->id }}">
        <td class="px-6 py-4 whitespace-nowrap">
            {{$field->order}}
        </td>
        <td class="px-6 py-4 whitespace-nowrap">
            <a href="{{route("fields.show", $field->id)}}" class="text-indigo-600 hover:text-blue-900">{{$field->title}}</a>
        </td>
        <td class="px-6 py-4 whitespace-nowrap">
            {{$field->database_type}}
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
            {{$field->status}}
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
            {{$field->required}}
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
            <a href="{{route("fields.edit", $field->id)}}" class="text-indigo-600 hover:text-indigo-900">Edit</a>
        </td>
    </tr>
@endforeach
<!-- More items... -->
</tbody>
