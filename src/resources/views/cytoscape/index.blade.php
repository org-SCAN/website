@section('title',"View network graph")
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Network graph') }}
        </h2>
    </x-slot>
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1, maximum-scale=1">


    <script src="https://cdnjs.cloudflare.com/ajax/libs/lodash.js/4.17.10/lodash.js"></script>

    <style>
        #cy {
            position: absolute;
            top: 450px;
            left: 0;
            bottom: 0;
            right: 0;
            z-index: 0;
            height: 100%;
        }

        h1 {
            opacity: 0.5;
            font-size: 1em;
            font-weight: bold;
        }

        #buttons {
            position: absolute;
            right: 0;
            bottom: 0;
            z-index: 99999;
        }
    </style>
    <script src="js/cytoscape/index.js"></script>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex flex-col">
                <h1>Graph</h1>
                <div class="rounded-lg px-2">
                    @foreach(\App\Models\Relation::all() as $relation)
                        <i class="fas fa-circle" style="color: #{{$relation->color}}"> {{$relation->name}}</i>
                    @endforeach
                </div>

                <div class="block mb-8 mt-8">
                    <a class="bg-red-200 hover:bg-red-300 text-black hover:text-black font-bold py-2 px-4 rounded"
                       href="?">Clear</a>
                    <a class="bg-blue-200 hover:bg-blue-300 text-black hover:text-black font-bold py-2 px-4 rounded"
                       href="{{ url()->current().'?'.http_build_query(array_merge(request()->all(),['calcul' => "betweennessCentrality"])) }}">Calculate
                        betweenness Centrality</a>
                    <div class="mt-3 mb-2">
                        @livewire("link-select-dropdown", ['label' => "from", 'placeholder' => '-- Select the first
                        person --', 'datas' => $refugees, "selected_value" => (isset($_GET['from']) ? $_GET['from'] :
                        "")])
                        @stack('scripts')
                    </div>
                    <div class="mt-2 mb-2">
                        @livewire("link-select-dropdown", ['label' => "to", 'placeholder' => '-- Select the second
                        person --', 'datas' => $refugees, "selected_value" => (isset($_GET['to']) ? $_GET['to'] : "")])
                        @stack('scripts')
                    </div>


                </div>
                <!--
                <a href="?layout=cise">Cise</a>
                <a href="?layout=dagre">Dagre</a>
                <a href="?layout=fcose">Fcose</a>
                -->


                <hr>
                <div id="cy"></div>
            </div>
        </div>
    </div>
</x-app-layout>
