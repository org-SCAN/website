@php use App\Models\ListRelation; @endphp
@php use App\Models\ListRole; @endphp
@section('title',"View network graph")
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Network graph') }}
        </h2>
    </x-slot>
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1, maximum-scale=1">


    <script src="https://cdnjs.cloudflare.com/ajax/libs/lodash.js/4.17.21/lodash.min.js"
            integrity="sha512-WFN04846sdKMIP5LKNphMaWzU7YpMyCU245etK3g/2ARYbPK9Ub18eG+ljU96qKRCWh+quCY7yefSmlkQw1ANQ=="
            crossorigin="anonymous" referrerpolicy="no-referrer">
    </script>

    <style>

        html,body {
            height: 100%;
            margin: 0;
        }

        #cy {

            height:70vh;
            position: relative;
        }
    </style>
    <script src="js/cytoscape/vendor.js"></script>

    <script src="js/cytoscape/cytoscape.js"></script>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex flex-col">
                <div class="container">
                        <div class="row h/6">
                            <div class="col-md-2">
                                <button class="bg-red-200 hover:bg-red-300 text-black hover:text-black font-bold py-2 px-4 rounded" id="clear">
                                    Clear
                                </button>
                            </div>
                            <div class="col-md-2">
                                <button class="bg-blue-200 hover:bg-blue-300 text-black hover:text-black font-bold py-2 px-4 rounded"
                                        id="betweenness_centrality">
                                    Show betweenness Centrality
                                </button>
                            </div>
                            <div class="col-md-2">

                                <button id="save" class="bg-green-200 hover:bg-green-300 text-black hover:text-black font-bold py-2 px-4 rounded">
                                    Save
                                </button>
                            </div>
                            <div class="col-md-3">
                                <div class="mt-3 mb-2">
                                    @livewire("link-select-dropdown", ['label' => "from", 'placeholder' => '-- Select the first
                                    person --', 'datas' => $refugees, "selected_value" => (isset($_GET['from']) ? $_GET['from'] :
                                    "")])
                                    @stack('scripts')

                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mt-2 mb-2">
                                    @livewire("link-select-dropdown", ['label' => "to", 'placeholder' => '-- Select the second
                                    person --', 'datas' => $refugees, "selected_value" => (isset($_GET['to']) ? $_GET['to'] : "")])
                                    @stack('scripts')
                                </div>
                            </div>
                            <div class="rounded-lg px-2">
                                @foreach(ListRelation::all() as $relation)
                                    <i class="fas fa-circle" style="color: {{ $relation->color }}"> {{$relation->name}}</i>
                                @endforeach
                            </div>
                            <div class="rounded-lg px-2">

                                @foreach(ListRole::all() as $role)
                                    <i class="fas fa-circle" style="color: {{ $role->color }}"> {{$role->displayed_value_content}}</i>
                                @endforeach
                            </div>
                            <hr>
                        </div>

            </div>
        </div>
    </div>
        <div class="ml-5 mr-5">
            <div id="cyWrapper"  style="border: 1px solid lightgrey;">
                <div id="cy" class="h5/6"></div>
            </div>
        </div>
</x-app-layout>
