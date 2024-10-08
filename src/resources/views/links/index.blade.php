@php use App\Models\Field;use App\Models\Link; @endphp
@section('title', __('links/index.title'))
@livewireStyles
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('links/index.relations') }}
        </h2>
    </x-slot>
    @if(!Field::hasBestDescriptiveValue())
        <div class="alert alert-danger" role="alert">
            <strong>
                {{ __('links/index.no_best_descriptive_field_warning') }}
            </strong>
        </div>
    @endif
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="block mb-8">
                @can('create', Link::class)
                    <a href="{{ route("links.create", []) }}"
                       class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                        {{ __('links/index.add_relation') }}
                    </a>
                @endcan
                @can('createFromJson', Link::class)
                    <a href="{{ route("links.create_from_json") }}"
                       class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                        {{ __('links/index.add_relation_from_json') }}
                    </a>
                @endcan
            </div>
            <div class="flex flex-col">
                <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                    <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                        <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg p-2">
                            <table id="links" class="display">
                                <thead>
                                <tr>
                                    <th class="toFilter">{{ __('links/index.from') }}</th>
                                    <th class="toFilter">{{ __('links/index.relation') }}</th>
                                    <th class="toFilter">{{ __('links/index.to') }}</th>
                                    <th class="toFilter">{{ __('links/index.date') }}</th>
                                    @can('update', $links->first())
                                        <th></th>
                                    @endcan
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($links as $link)
                                    @php
                                        $types = [
                                            'refugeeFrom' => 'person',
                                            'refugeeTo' => 'person',
                                            'eventFrom' => 'event',
                                            'eventTo' => 'event',
                                            'placeFrom' => 'place',
                                            'placeTo' => 'place',
                                        ];
                                        $fromType = null;
                                        $toType = null;
                                        $from = null;
                                        $to = null;

                                        foreach ($types as $relation => $route) {
                                            if ($link->$relation && !$from) {
                                                $from = $link->$relation;
                                                $fromType = $route;
                                            } elseif ($link->$relation && !$to) {
                                                $to = $link->$relation;
                                                $toType = $route;
                                            }
                                        }
                                    @endphp

                                    @if ($from && $to)
                                        <tr>
                                            <td>
                                                <a href="{{ route($fromType . '.show', $from->id) }}">{{ $from->best_descriptive_value ?? $from->name }}</a>
                                            </td>
                                            <td>{{ $link->relation->displayed_value_content }}</td>
                                            <td>
                                                <a href="{{ route($toType . '.show', $to->id) }}">{{ $to->best_descriptive_value ?? $to->name }}</a>
                                            </td>
                                            <td>{{ $link->date->format('d/m/Y') }}</td>
                                            @can('update', $link)
                                                <td><a href="{{ route('links.edit', $link->id) }}">{{ __('common.edit') }}</a></td>
                                            @endcan
                                        </tr>
                                    @endif
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
<link rel="stylesheet" type="text/css"
      href="https://cdn.datatables.net/v/dt/jq-3.6.0/jszip-2.5.0/dt-1.11.4/b-2.2.2/b-colvis-2.2.2/b-html5-2.2.2/b-print-2.2.2/date-1.1.2/fc-4.0.2/fh-3.2.2/kt-2.6.4/r-2.2.9/sc-2.0.5/sb-1.3.1/sp-1.4.0/datatables.min.css"/>

<script type="text/javascript"
        src="https://cdn.datatables.net/v/dt/jq-3.6.0/jszip-2.5.0/dt-1.11.4/b-2.2.2/b-colvis-2.2.2/b-html5-2.2.2/b-print-2.2.2/date-1.1.2/fc-4.0.2/fh-3.2.2/kt-2.6.4/r-2.2.9/sc-2.0.5/sb-1.3.1/sp-1.4.0/datatables.min.js"></script>

<script
    src="https://cdn.datatables.net/plug-ins/1.11.3/features/fuzzySearch/dataTables.fuzzySearch.js"
    integrity="sha384-7Dn5Qjo6DjZC0FYyj6coY+zPOZuQG2auoMgksD4y5B7ifeecIQAzJFKKOC6L84pF"
    crossorigin="anonymous"></script>

<script>
    $(document).ready(function () {
        $('#links thead tr')
            .clone(true)
            .addClass('filters')
            .appendTo('#links thead');

        $('#links').DataTable({
            fuzzySearch: true,
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
