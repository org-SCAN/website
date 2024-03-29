@section('title',"Edit ".$link->refugeeFrom->best_descriptive_value." and ".$link->refugeeTo->best_descriptive_value." relation")
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Edit : <b>{{$link->refugeeFrom->best_descriptive_value}}
                and {{$link->refugeeTo->best_descriptive_value}}</b>'s relationship
        </h2>
    </x-slot>

    <div>
        <div class="max-w-4xl mx-auto py-10 sm:px-6 lg:px-8">
            <div class="block mb-8">
                @can("delete", $link)
                <form action="{{route('links.destroy', $link->id)}}" method="POST"
                      class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                    @method('DELETE')
                    @csrf
                    @endcan
                    <a href="{{ route('links.index')  }}"
                       class="bg-gray-200 hover:bg-gray-300 text-black font-bold py-2 px-4 rounded">Back</a>
                    @can("delete", $link)
                    <button type="submit"
                            class="flex-shrink-0 bg-red-200 hover:bg-red-300 text-black font-bold py-2 px-4 rounded">
                        Delete
                    </button>
                </form>
                @endcan
            </div>
            <div class="mt-5 md:mt-0 md:col-span-2">
                <form method="post" action="{{ route('links.update', $link) }}">
                    @csrf
                    @method('PUT')
                    <div class="shadow overflow-hidden sm:rounded-md">

                        <!--  Relation SECTION  -->
                        <div class="px-4 py-5 bg-white sm:p-6">

                            @php($form_elem = "relation_id")
                            <label for="{{$form_elem}}" class="block font-medium text-md text-gray-700">Relation</label>

                            @php( $list = $lists["relations"])
                            <x-form-select name="{{$form_elem}}" :options="$list" id="{{$form_elem}}" class="form-input rounded-md shadow-sm mt-1 block w-full" />

                            @error($form_elem)
                            <p class="text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Date SECTION -->
                        <div class="px-4 py-5 bg-white sm:p-6">
                            @livewire("forms.form", [
                            'form_elem' => 'date',
                            'type' => "date",
                            'title' => "Date",
                            'previous' => $link->date->format('Y-m-d'),])
                        </div>
                        <!--  detail SECTION  -->
                        <div class="px-4 py-5 bg-white sm:p-6">
                            @livewire("forms.form", [
                            'form_elem' => 'detail',
                            'type' => "text",
                            'title' => "Detail",
                            'placeHolder' => "Father",
                            'previous' => $link->detail])
                        </div>


                        <div class="flex items-center justify-end px-4 py-3 bg-gray-50 text-right sm:px-6">
                            <button class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray disabled:opacity-25 transition ease-in-out duration-150">
                                Save
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
