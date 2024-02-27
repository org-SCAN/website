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
                                    <td>ID</td>
                                    <td>Name</td>
                                    <td>Latitude</td>
                                    <td>Longitude</td>
                                    <td>Description</td>
                                    <td>Actions</td>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($places as $key => $value)
                                    <tr>
                                        <td>{{ $value->id }}</td>
                                        <td>{{ $value->name }}</td>
                                        <td>{{ $value->lat }}</td>
                                        <td>{{ $value->lon }}</td>
                                        <td>{{ $value->description }}</td>

                                        <!-- we will also add show, edit, and delete buttons -->
                                        <td class="flex space-x-1">

                                            <a class="btn btn-small bg-green-500 hover:bg-green-700 text-white"
                                               href="{{ URL::to('place/' . $value->id) }}">Show</a>

                                            <a class="btn btn-small btn-info"
                                               href="{{ URL::to('place/' . $value->id . '/edit') }}">Edit</a>

                                            <form action="{{ route('place.destroy', $value->id) }}" method="POST"
                                                  class="delete">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-small btn-danger">Delete</button>
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
    $(".delete").on("submit", function () {
        return confirm("Are you sure?");
    });
</script>
