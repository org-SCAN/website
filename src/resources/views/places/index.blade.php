@php use App\Models\Places @endphp

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('places/index.places') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="block mb-8">
                <a href="{{ route("places.create") }}"
                   class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                    {{ __('places/index.add_item') }}
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
                                        <td>

                                            <!-- delete the shark (uses the destroy method DESTROY /sharks/{id} -->
                                            <!-- we will add this later since its a little more complicated than the other two buttons -->

                                            <!-- show the shark (uses the show method found at GET /sharks/{id} -->
                                            <a class="btn btn-small btn-success" href="{{ URL::to('places/' . $value->id) }}">Show</a>

                                            <!-- edit this shark (uses the edit method found at GET /sharks/{id}/edit -->
                                            <a class="btn btn-small btn-info" href="{{ URL::to('places/' . $value->id . '/edit') }}">Edit</a>

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
