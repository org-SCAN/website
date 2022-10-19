@section('title',"Create the list's fields")
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Create the {{ $listControl->title }} list's fields
        </h2>
    </x-slot>
    <div>
        <div class="max-w-4xl mx-auto py-10 sm:px-6 lg:px-8">
            <div class="block mb-8">
                <p>
                    In this section, you may define all the fields that are required to describe your list.
                    As an example you can create 'name', 'inhabitants' if you want to create a list related to Cities.
                </p>
            </div>
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="mt-5 md:mt-0 md:col-span-2">
                <form method="post" action="{{ route('lists_control.store_fields', $listControl) }}">
                    @csrf
                    <div class="shadow overflow-hidden sm:rounded-md">

                        <!--  Field 0 SECTION  -->
                        <div class="px-4 py-4 bg-white sm:p-6">
                            @php($form_elem = "fields[0]")
                            <label for="{{$form_elem}}" class="block font-medium text-md text-gray-700">Field's
                                title</label>
                            <input type="text" name="{{$form_elem}}" id="{{$form_elem}}"
                                   class="form-input rounded-md shadow-sm mt-1 block w-full" placeholder="e.g. name"/>
                            <small id="{{$form_elem}}Help" class="block font-medium text-sm text-gray-500">The name of
                                the field</small>
                            @error('fields')
                            <p class="text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            @error($form_elem)
                            <p class="text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div id="newRow"></div>


                        <div class="px-4 bg-white sm:p-6">
                            <button type="button" id="addRow"
                                    class="inline-flex items-center px-4 py-2 bg-green-400 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-300 active:bg-green-900 focus:outline-none focus:border-green-400 focus:shadow-outline-gray disabled:opacity-25 ease-in-out duration-150">
                                Add row
                            </button>
                        </div>
                        <div class="flex items-center justify-end px-4 py-3 bg-gray-50 text-right sm:px-6">
                            <button
                                class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray disabled:opacity-25 transition ease-in-out duration-150">
                                Next
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
<script type="text/javascript">
    // add row
    var counter = 1;
    $("#addRow").click(function () {
        var html = '';
        html += '<div class="px-4 py-4 bg-white sm:p-6" id="inputFormRow">';
        html += '<div class="row">'
        html += '<div class="col-12">'
        html += '<label for="fields[' + counter + ']" class="block font-medium text-md text-gray-700">Field\'s title</label>';
        html += '</div>'
        html += '<div class="col-10">'
        html += '<input type="text" name="fields[' + counter + ']" id="fields[' + counter + ']" class="form-input rounded-md shadow-sm mt-1 block w-full" placeholder="e.g. size" />';
        html += '</div>'
        html += '<div class="col-2 mt-2">';
        html += '<i id="removeRow" class="fa fa-trash text-red-500 hover:text-red-700" aria-hidden="true"></i>';
        html += '</div>'
        html += '<div class="col-12">'
        html += '<small id="fields[' + counter + ']Help" class="block font-medium text-sm text-gray-500">The name of the field</small>';
        html += '</div>'
        html += '</div>'
        html += '</div>'
        counter++;
        $('#newRow').append(html);
    });

    // remove row
    $(document).on('click', '#removeRow', function () {
        $(this).closest('#inputFormRow').remove();
    });
</script>
