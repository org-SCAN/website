@php use App\Models\Field;use App\Models\Refugee; @endphp
@section('title', __('person/index.view_items'))

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('person/index.items') }}
        </h2>
    </x-slot>

    @if(!Field::hasBestDescriptiveValue())
        <div class="alert alert-danger" role="alert">
            <strong>{{ __('person/index.no_best_descriptive_field') }}</strong>
        </div>
    @endif
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="block mb-8 flex space-x-2"">
                @can('create', Refugee::class)
                    <a href="{{ route("person.create") }}"
                       class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                        {{ __('person/index.add_item') }}
                    </a>
                @endcan
                @can('createFromJson', Refugee::class)
                    <a href="{{ route("person.create_from_json") }}"
                       class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                        {{ __('person/index.import_items') }}
                    </a>
                @endcan
            </div>
            <div class="flex flex-col">
                <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                    <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                        <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg p-2">
                            <table id="person" class="display">
                                <caption class="sr-only">{{ __('person/index.items') }}</caption>
                                <thead>
                                <tr>
                                    @foreach($fields as $field)
                                        <th>{{ $field->title }}</th>
                                    @endforeach
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($refugees as $refugee_id => $refugee)
                                    <tr>
                                        @foreach($fields ?? '' as $field)
                                            <td>
                                                @if($field->best_descriptive_value)
                                                    @can('view', Refugee::find($refugee_id))
                                                        <a href="{{route('person.show',$refugee_id)}}">
                                                            {{ $refugee[$field->id] }}
                                                        </a>
                                                    @endcan()
                                                    @cannot('view', Refugee::find($refugee_id))
                                                        {{ $refugee[$field->id] }}
                                                    @endcannot()
                                                @else
                                                    @if($field->range && key_exists($field->id, $refugee))
                                                        <div class="row">
                                                            <div class="col-4 text-center">
                                                                <span class="text-gray-400 ">
                                                                     {{ json_decode($refugee[$field->id],true)['min'] ?? "Ø"}}
                                                                </span>
                                                            </div>
                                                            <div class="col-4 text-center">
                                                                <span class="">
                                                                     {{ json_decode($refugee[$field->id],true)['current'] ?? "Ø"}}
                                                                </span>
                                                            </div>
                                                            <div class="col-4 text-center">
                                                                <span class="text-gray-400">
                                                                    {{ json_decode($refugee[$field->id],true)['max'] ?? "Ø"}}
                                                                </span>
                                                            </div>
                                                    @else
                                                        {{ $refugee[$field->id] ?? ''}}
                                                    @endif
                                                @endif
                                            </td>
                                        @endforeach
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
<script
    src="https://cdn.datatables.net/plug-ins/1.11.3/features/fuzzySearch/dataTables.fuzzySearch.js"
    integrity="sha384-7Dn5Qjo6DjZC0FYyj6coY+zPOZuQG2auoMgksD4y5B7ifeecIQAzJFKKOC6L84pF"
    crossorigin="anonymous"></script>
<script>
    $(document).ready(function () {
        $('#person thead tr')
            .clone(true)
            .addClass('filters')
            .appendTo('#person thead');

        $('#person').DataTable({
            fuzzySearch: true,
            orderCellsTop: true,
            fixedHeader: true,
            initComplete: function () {
                var api = this.api();

                // For each column
                api
                    .columns()
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

