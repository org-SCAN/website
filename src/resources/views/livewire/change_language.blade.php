<x-action-section>
    <x-slot name="title">
        {{ __('livewire/change_language.change_language') }}
    </x-slot>

    <x-slot name="description">
        {{ __('livewire/change_language.change_language_description') }}
    </x-slot>

    <x-slot name="content">
        <div class="flex items-center mt-4">
            <form class="inline-block" action="{{route('user.change_language',  $user->id)}}" method="POST">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                @livewire("forms.form", [
                    "form_elem" => "language_id",
                    "type" => "select-dropdown",
                    "title" => __("livewire/change_language.form_title"),
                    "placeHolder" => __("livewire/change_language.form_placeholder"),
                    "associated_list" => $languages,
                    "previous" => $user->language->id ?? null,
                    ])



                <button
                        class="inline-flex items-center px-4 py-2 mt-4 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray disabled:opacity-25 transition ease-in-out duration-150">
                    {{ __('livewire/change_language.change_language') }}
                </button>
            </form>
        </div>
    </x-slot>
</x-action-section>