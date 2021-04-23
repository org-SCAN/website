<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Relation graph') }}
        </h2>
    </x-slot><meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1, maximum-scale=1">

    <script src="https://unpkg.com/cytoscape/dist/cytoscape.min.js"></script>

    <!-- for testing with local version of cytoscape.js -->
    <!--<script src="../cytoscape.js/build/cytoscape.js"></script>-->

    <script src="https://cdnjs.cloudflare.com/ajax/libs/lodash.js/4.17.10/lodash.js"></script>
    <script src="cytoscape-edgehandles.js"></script>

    <style>
        body {
            font-family: helvetica neue, helvetica, liberation sans, arial, sans-serif;
            font-size: 14px;
        }

        #cy {
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            right: 0;
            z-index: 999;
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
    <script src="https://cdn.jsdelivr.net/npm/cytoscape-dagre@2.3.2/cytoscape-dagre.min.js"></script>
    <script>
        cytoscape.use(cytoscapeDagre);

        document.addEventListener('DOMContentLoaded', function(){

            var cy = cytoscape({

                container: document.getElementById('cy'), // container to render in

                elements: [ // list of graph elements to start with
                    @foreach(\App\Models\Link::where("deleted", 0)->get() as $relation)
                        { // node a
                            data: { id: '{{$relation->getRefugee1Id()}}', name: '{{$relation->refugee1}}' }
                        },
                    @endforeach
                    @foreach(\App\Models\Link::where("deleted", 0)->get() as $relation)
                        { // node a
                            data: { id: '{{$relation->getRefugee2Id()}}', name: '{{$relation->refugee2}}' }
                        },
                    @endforeach
                    @foreach(\App\Models\Link::where("deleted", 0)->get() as $relation)
                    { // edge ab
                        data: { id: '{{$relation->id}}', source: '{{$relation->getRefugee1Id()}}', target: '{{$relation->getRefugee2Id()}}' }
                    },
                    @endforeach
                ],

                style: [ // the stylesheet for the graph
                    {
                        selector: 'node',
                        style: {
                            'background-color': '#666',
                            'label': 'data(name)'
                        }
                    },

                    {
                        selector: 'edge',
                        style: {
                            'width': 3,
                            'line-color': '#ccc',
                            'target-arrow-color': '#ccc',
                            'target-arrow-shape': 'triangle',
                            'curve-style': 'bezier'
                        }
                    }
                ],


            });
            cy.layout({
                name: 'breadthfirst'
            }).run();

            var eh = cy.edgehandles();

            document.querySelector('#draw-on').addEventListener('click', function() {
                eh.enableDrawMode();
            });

            document.querySelector('#draw-off').addEventListener('click', function() {
                eh.disableDrawMode();
            });

            document.querySelector('#start').addEventListener('click', function() {
                eh.start( cy.$('node:selected') );
            });

        });
    </script>
    </head>

    <body>
    <h1>cytoscape-edgehandles demo</h1>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex flex-col">
                <div id="cy"></div>
            </div>
        </div>
    </div>

    <div id="buttons">
        <button id="start">Start on selected</button>
        <button id="draw-on">Draw mode on</button>
        <button id="draw-off">Draw mode off</button>
    </div>
</x-app-layout>
