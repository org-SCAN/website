@php use App\Models\ListRelation; @endphp
@section('title', __('links/edit.edit_relation_between', ['item' => $link->refugeeFrom->best_descriptive_value, 'item2' => $link->refugeeTo->best_descriptive_value]))
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('links/edit.edit_relation_between', ['item' => $link->refugeeFrom->best_descriptive_value, 'item2' => $link->refugeeTo->best_descriptive_value]) }}
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
                       class="bg-gray-200 hover:bg-gray-300 text-black font-bold py-2 px-4 rounded">{{ __('common.back') }}</a>
                    @can("delete", $link)
                    <button type="submit"
                            class="flex-shrink-0 bg-red-200 hover:bg-red-300 text-black font-bold py-2 px-4 rounded">
                        {{ __('common.delete') }}
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
                            @livewire("forms.form", [
                                'form_elem' => $form_elem,
                                'type' => 'select-dropdown',
                                'title' => __('links/create.relation'),
                                'placeHolder' => __('links/create.select_relation'),
                                'associated_list' => ListRelation::list(),
                                'previous' => $link->relation->id,
                                ])
                        </div>

                        <!-- Date SECTION -->
                        <div class="px-4 py-5 bg-white sm:p-6">
                            @livewire("forms.form", [
                            'form_elem' => 'date',
                            'type' => "date",
                            'title' => __('links/create.date'),
                            'previous' => $link->date->format('Y-m-d'),])
                        </div>
                        <!--  detail SECTION  -->
                        <div class="px-4 py-5 bg-white sm:p-6">
                            @livewire("forms.form", [
                            'form_elem' => 'detail',
                            'type' => "text",
                            'title' => __('links/create.detail'),
                            'placeHolder' => __('links/create.detail_placeholder'),
                            'previous' => $link->detail])
                        </div>


                        <div class="flex items-center justify-end px-4 py-3 bg-gray-50 text-right sm:px-6">
                            <button class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray disabled:opacity-25 transition ease-in-out duration-150">
                                {{ __('common.save') }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
