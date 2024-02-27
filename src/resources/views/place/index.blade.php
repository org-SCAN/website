@php use App\Models\Place @endphp

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('place/index.place') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="block mb-8">
                <a href="{{ route("place.create") }}"
                   class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                    {{ __('place/index.add_item') }}
                </a>
            </div>
            <div class="flex flex-col">
                <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                    <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                        <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg p-2">
                            <table class="table table-striped table-bordered">
                                <thead>
                                <tr>
                                    <td>{{__('place/index.fields.name')}}</td>
                                    <td>{{__('place/index.fields.lat')}}</td>
                                    <td>{{__('place/index.fields.lon')}}</td>
                                    <td>{{__('place/index.fields.description')}}</td>
                                    <td>{{__('place/index.fields.actions')}}</td>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($places as $key => $place)
                                    <tr>
                                        <td>{{ $place->name }}</td>
                                        <td>{{ $place->lat }}</td>
                                        <td>{{ $place->lon }}</td>
                                        <td>{{ $place->description }}</td>

                                        <!-- we will also add show, edit, and delete buttons -->
                                        <td class="flex space-x-1">

                                            <a class="btn btn-small bg-green-500 hover:bg-green-700 text-white"
                                               href="{{ route('place.show', $place->id) }}">{{__('place/index.show')}}</a>

                                            <a class="btn btn-small btn-info"
                                               href="{{ route('place.edit', $place->id) }}">{{__('place/index.edit')}}</a>

                                            <form action="{{ route('place.destroy', ['place' => $place]) }}" method="POST" class="delete-form">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-small btn-danger delete-btn">{{__('place/index.delete')}}</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<script>
document.addEventListener('DOMContentLoaded', function () {
    var deleteForms = document.querySelectorAll('.delete-form');

    deleteForms.forEach(function (form) {
        form.addEventListener('submit', function (event) {
            var deleteBtn = form.querySelector('.delete-btn');
            var confirmationMessage = "{{ __('place/index.delete_confirm') }}";

            if (!confirm(confirmationMessage)) {
                event.preventDefault();
            }
        });
    });
});
</script>

