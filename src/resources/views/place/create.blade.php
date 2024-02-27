@php use App\Models\Place @endphp
@section('title', __('place/create.title'))

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('place/index.place') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="block mb-8">
                <a href="{{ route('place.index') }}"
                   class="bg-gray-200 hover:bg-gray-300 text-black font-bold py-2 px-4 rounded">
                    {{__('common.back')}}
                </a>
            </div>

            <form class="w-px-500 p-3 p-md-3" action="{{ route('place.store') }}" method="post"
                  enctype="multipart/form-data">
                @csrf
                <div class="row mb-3">
                    <label class="col-sm-3 col-form-label">{{__('place/create.fields.name')}}</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control @error('name') is-invalid @enderror" name="name"
                               placeholder="{{__('place/create.placeholders.name')}}">
                        @error('name')
                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-sm-3 col-form-label">{{__('place/create.fields.lat')}}</label>
                    <div class="col-sm-9">
                        <input type="number" step="0.000001" class="form-control @error('lat') is-invalid @enderror"
                               name="lat" placeholder="{{__('place/create.placeholders.lat')}}">
                        @error('lat')
                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                        @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-sm-3 col-form-label">{{__('create/edit.fields.lon')}}</label>
                    <div class="col-sm-9">
                        <input type="number" step="0.000001" class="form-control @error('lon') is-invalid @enderror"
                               name="lon" placeholder="{{__('place/create.placeholders.lon')}}">
                        @error('lon')
                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                        @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-sm-3 col-form-label">{{__('place/create.fields.description')}}</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control @error('description') is-invalid @enderror"
                               name="description" placeholder="{{__('place/create.placeholders.description')}}">
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
                        <button type="submit" class="btn btn-success btn-block text-white">{{__('common.save')}}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
