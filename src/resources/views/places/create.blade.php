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
                <a href="{{ route('places.index') }}"
                   class="bg-gray-200 hover:bg-gray-300 text-black font-bold py-2 px-4 rounded">
                    Back
                </a>
            </div>

            <form class="w-px-500 p-3 p-md-3" action="{{ route('places.store') }}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="row mb-3">
                    <label class="col-sm-3 col-form-label">Name</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" placeholder="Name">
                        @error('name')
                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                        @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-sm-3 col-form-label">Latitude</label>
                    <div class="col-sm-9">
                        <input type="number" step="0.000001" class="form-control @error('lat') is-invalid @enderror" name="lat" placeholder="Latitude">
                        @error('lat')
                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                        @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-sm-3 col-form-label">Longitude</label>
                    <div class="col-sm-9">
                        <input type="number" step="0.000001" class="form-control @error('lon') is-invalid @enderror" name="lon" placeholder="Longitude">
                        @error('lon')
                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                        @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-sm-3 col-form-label">Description</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control @error('description') is-invalid @enderror" name="description" placeholder="Description">
                        @error('description')
                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                        @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-sm-3 col-form-label"></label>
                    <div class="col-sm-9">
                        <button type="submit" class="btn btn-success btn-block text-white">Submit</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
