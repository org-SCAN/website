@php use App\Models\Place @endphp
@section('title', __('place/index.view_all_places'))
@livewireStyles
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('place/index.places') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="block mb-8">
                @can('create', Place::class)
                    <a href="{{ route("place.create") }}"
                       class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                        {{ __('place/index.add_place') }}
                    </a>
                @endcan
            </div>
            <div class="flex flex-col">
                <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                    <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                        <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg p-2">
                            <table id="places" class="display">
                                <caption class="sr-only">{{__('place/index.view_all_places')}}</caption>
                                <thead>
                                <tr>
                                    <th class="toFilter">{{__('place/index.fields.name')}}</th>
                                    <th class="toFilter">{{__('place/index.fields.lat')}}</th>
                                    <th class="toFilter">{{__('place/index.fields.lon')}}</th>
                                    <th class="toFilter">{{__('place/index.fields.description')}}</th>
                                    <th class="toFilter">{{__('place/index.fields.area')}}</th>
                                    @can('update', $places->first())
                                        <th></th>
                                    @endcan
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($places as $key => $place)
                                    <tr>
                                        <td>
                                            <a href="{{route('place.show',  $place->id)}}"> {{ $place->name }}</a>
                                        </td>
                                        <td>{{ json_decode($place->coordinates, true)['lat'] }}</td>
                                        <td>{{ json_decode($place->coordinates, true)['long'] }}</td>
                                        <td>{{ $place->description }}</td>
                                        <td>{{ count(json_decode($place->area, true)['polygons']) }}</td>
                                        @can('update', $place)
                                            <td><a href="{{ route('place.edit', $place->id) }}">{{__('common.edit')}}</a></td>
                                        @endcan
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
<link rel="stylesheet"
      href="https://cdn.datatables.net/v/dt/jq-3.6.0/jszip-2.5.0/dt-1.11.4/b-2.2.2/b-colvis-2.2.2/b-html5-2.2.2/b-print-2.2.2/date-1.1.2/fc-4.0.2/fh-3.2.2/kt-2.6.4/r-2.2.9/sc-2.0.5/sb-1.3.1/sp-1.4.0/datatables.min.css"
      integrity="sha384-Zt18T5BCHWpEjWpkZH11WEAug/T7djz4tR5qA4Gtohb1nnpaNztkYYViNsVcUkEd" crossorigin="anonymous">

<script
    src="https://cdn.datatables.net/v/dt/jq-3.6.0/jszip-2.5.0/dt-1.11.4/b-2.2.2/b-colvis-2.2.2/b-html5-2.2.2/b-print-2.2.2/date-1.1.2/fc-4.0.2/fh-3.2.2/kt-2.6.4/r-2.2.9/sc-2.0.5/sb-1.3.1/sp-1.4.0/datatables.min.js"
    integrity="sha384-33Dh7Paf7BKMZ84cYXJONPZfgFUgZqvR6KYZGWtvU1u4QQRTy0nZCrGlo7qNZ3f0"
    crossorigin="anonymous"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    var deleteForms = document.querySelectorAll('.delete-form');

    deleteForms.forEach(function (form) {
        form.addEventListener('submit', function (event) {
            var confirmationMessage = "{{ __('place/index.delete_confirm') }}";

            if (!confirm(confirmationMessage)) {
                event.preventDefault();
            }
        });
    });
});

$(document).ready(function () {
    $('#places thead tr')
        .clone(true)
        .addClass('filters')
        .appendTo('#events thead');

    $('#places').DataTable({
        orderCellsTop: true,
        fixedHeader: true,
        initComplete: function () {
            var api = this.api();

            // For each column
            api
                .columns($('.toFilter'))
                .eq(0)
                .each(function (colIdx) {
                    // Set the header cell to contain the input element
                    var cell = $('.filters th').eq(
                        $(api.column(colIdx).header()).index()
                    );
                    var title = $(cell).text();
                    $(cell).html('<input type="text" placeholder="' + title + '" />');

                    // On every keypress in this input
                    $(
                        'input',
                        $('.filters th').eq($(api.column(colIdx).header()).index())
                    )
                        .off('keyup change')
                        .on('change', function (e) {
                            // Get the search value
                            $(this).attr('title', $(this).val());
                            var regexr = '({search})'; //$(this).parents('th').find('select').val();

                            var cursorPosition = this.selectionStart;
                            // Search the column for that value
                            api
                                .column(colIdx)
                                .search(
                                    this.value != ''
                                        ? regexr.replace('{search}', '(((' + this.value + ')))')
                                        : '',
                                    this.value != '',
                                    this.value == ''
                                )
                                .draw();
                        })
                        .on('keyup', function (e) {
                            e.stopPropagation();

                            $(this).trigger('change');
                            $(this)
                                .focus()[0]
                                .setSelectionRange(cursorPosition, cursorPosition);
                        });
                });
        },
    });
});
</script>

