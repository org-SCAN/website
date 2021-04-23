<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Relation graph') }}
        </h2>
    </x-slot><meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1, maximum-scale=1">



    <script src="https://cdnjs.cloudflare.com/ajax/libs/lodash.js/4.17.10/lodash.js"></script>

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
    <?php
    $refugees = \App\Models\Refugee::where('deleted',0)->get();
    $relations = \App\Models\Link::where('deleted',0)->get();
/*
    $nodes = array();
    foreach ($refugees as $refugee){
        $node["data"] = array();
        $node["data"]["id"] = $refugee->id;
        $node["data"]["name"] = $refugee->full_name;
        array_push($nodes, $node);
    }*/

    $links = array();
    $nodes = array();
    foreach ($relations as $relation){

        $node["data"] = array();
        $node["data"]["id"] = $relation->getRefugee1Id();
        $node["data"]["name"] = $relation->refugee1;
        array_push($nodes, $node);

        $node["data"] = array();
        $node["data"]["id"] = $relation->getRefugee2Id();
        $node["data"]["name"] = $relation->refugee2;
        array_push($nodes, $node);

        $link["data"] = array();
        $link["data"]["id"] = $relation->id;
        $link["data"]["label"] = $relation->relation;
        $link["data"]["source"] = $relation->getRefugee1Id();
        $link["data"]["target"] = $relation->getRefugee2Id();
        array_push($links, $link);
    }

   // file_put_contents("js/cytoscape/content.json",json_encode(array_merge($nodes, $links)));
    Storage::disk('public')->put('content.json', json_encode(array_merge($nodes, $links)));
    ?>
    <script src="js/cytoscape/index.js"></script>
    </head>

    <body>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex flex-col">
                <div id="cy"></div>
            </div>
        </div>
    </div>
</x-app-layout>
