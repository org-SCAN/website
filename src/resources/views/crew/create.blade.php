@php use App\Models\ListMatchingAlgorithm; @endphp
@php use App\Models\Crew; @endphp

@section('title', __('crew/create.create_new_team'))

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('crew/create.add_team') }}
        </h2>
    </x-slot>
    <div>
        <div class="max-w-4xl mx-auto py-10 sm:px-6 lg:px-8">
            <div class="block mb-8">
                <a href="{{ route('crew.index') }}"
                   class="bg-gray-300 hover:bg-gray-400 text-black font-bold py-2 px-4 rounded">
                    {{ __('common.back') }}
                </a>
            </div>
            <div class="mt-5 md:mt-0 md:col-span-2">
                <form method="post" action="{{ route('crew.store') }}">
                    @csrf
                    <div class="shadow overflow-hidden sm:rounded-md">
                        <div class="px-4 py-5 bg-white sm:p-6">
                            @php($form_elem = "name")
                            @livewire("forms.form", [
                                'form_elem' => $form_elem,
                                'type' => "text",
                                'title' => __("crew/create.name"),
                                'previous' => old($form_elem),
                            ])
                        </div>
                        <div class="px-4 py-5 bg-white sm:p-6">
                            @php($form_elem = "duplicate_algorithm_id")
                            @php($list = ListMatchingAlgorithm::list())
                            @livewire("forms.form", [
                                'form_elem' => $form_elem,
                                'type' => "select-dropdown",
                                'associated_list' => $list,
                                'title' => __("crew/create.duplicate_algorithm"),
                                'previous' => ListMatchingAlgorithm::where('is_default', '1')->first()->id ?? null
                            ])
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
