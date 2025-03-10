@php use App\Models\Field; @endphp
@section('title', __('person/create_from_json.title'))
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('person/create_from_json.header') }}
        </h2>
    </x-slot>
    <div>
        <div class="max-w-4xl mx-auto py-10 sm:px-6 lg:px-8">
            <div class="block mb-8">
                <a href="{{URL::previous() }}"
                   class="bg-gray-200 hover:bg-gray-300 text-black font-bold py-2 px-4 rounded">
                    {{ __('common.back') }}
                </a>
            </div>

            <blockquote class="relative border-l-4 pl-4 sm:pl-6 border-red-400 align-justify">
                <p class="text-gray-800 sm:text-xl dark:text-white ">
                    {!! __('person/create_from_json.import_instruction') !!}
                </p>
            </blockquote>

            <div class="mt-5 md:mt-0 md:col-span-2">
                <form method="post" action="{{route('person.store_from_json') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="shadow overflow-hidden sm:rounded-md">
                        <div class="px-4 py-5 bg-white sm:p-6">
                            <label for="import_person_file" class="block font-medium text-sm text-gray-700">
                                {{ __('person/create_from_json.upload_file_label') }}
                            </label>
                            <div class="flex flex-col flex-grow mb-3">
                                <div class="flex flex-col flex-grow mb-3">
                                    <div x-data="{ files: null }" id="FileUpload"
                                         class="block w-full py-2 px-3 relative bg-white appearance-none border-2 border-gray-300 border-dashed rounded-md hover:shadow-outline-gray">
                                        <input type="file"
                                               class="absolute inset-0 z-50 m-0 p-0 w-full h-full outline-none opacity-0"
                                               x-on:change="files = $event.target.files; console.log($event.target.files);"
                                               x-on:dragover="$el.classList.add('active')"
                                               x-on:dragleave="$el.classList.remove('active')"
                                               x-on:drop="$el.classList.remove('active')"
                                               accept="application/json, .csv, .xlsx, .xls"
                                               name="import_person_file"
                                               id="import_person_file"
                                        >
                                        <template x-if="files !== null">
                                            <div class="flex flex-col space-y-1">
                                                <template x-for="(_,index) in Array.from({ length: files.length })">
                                                    <div class="flex flex-row items-center space-x-2">
                                                        <span class="font-medium text-gray-900"
                                                              x-text="files[index].name">{{ __('person/create_from_json.uploading') }}</span>
                                                        <span class="text-xs self-end text-gray-500"
                                                              x-text="filesize(files[index].size)">...</span>
                                                    </div>
                                                </template>
                                            </div>
                                        </template>
                                        <template x-if="files === null">
                                            <div class="flex flex-col space-y-2 items-center justify-center">
                                                <i class="fas fa-cloud-upload-alt fa-3x text-currentColor"></i>
                                                <p class="text-gray-700">{{ __('person/create_from_json.drag_files_instruction') }}</p>
                                                <a href="javascript:void(0)"
                                                   class="flex items-center mx-auto py-2 px-4 text-white text-center font-medium border border-transparent rounded-md outline-none bg-gray-700">
                                                    {{ __('person/create_from_json.select_file_button') }}
                                                </a>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                            </div>


                            @error("*")
                            @php($replacements = Field::where('crew_id', auth()->user()->crew->id)->get()->pluck('title', 'id')->toArray())

                            <p class="text-sm text-red-600">{{ str_replace(array_keys($replacements), $replacements, $message) }}</p>
                            @enderror
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
<script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js"></script>
<script src="https://cdn.filesizejs.com/filesize.min.js"></script>
