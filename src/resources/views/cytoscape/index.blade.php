@php use App\Models\ListRelation; @endphp
@php use App\Models\ListRole; @endphp
@section('title', __('cytoscape/index.view_network_graph'))
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('cytoscape/index.network_graph') }}
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

    <div class="py-6">
            <div class="flex flex-col mb-2">
                <div class="ml-5 mr-5">
                        <div class="row h/6">
                            <div class="col-xl-3 col-md-12">
                                <div class="row">
                                    <div class="col-12">
                                        <h3>{{ __('cytoscape/index.legend') }}</h3>
                                    </div>
                                    <div class="rounded-lg px-2 col-12">
                                        <span class="legendRelation" id="">
                                            {{ __('cytoscape/index.relations') }} :
                                        </span>
                                        <div class="legendRelation">
                                            @foreach($used_relations as $relation)
                                                <em class="fas fa-circle" style="color: {{ $relation->color }}"> {{$relation->name}}</em>
                                            @endforeach
                                        </div>
                                    </div>
                                    <div class="rounded-lg px-2 col-12">
                                        <span class="legendList">
                                            {{ __('cytoscape/index.nodes') }} :
                                        </span>
                                        <div class="legendList" id="legendList">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-6 col-lg-8 col-md-12 ">
                                <div class="row">
                                    <div class="col-md-6 p-2">
                                        <div class="row">
                                            <div class="col-12">
                                                <h3>{{ __('cytoscape/index.options') }}</h3>
                                            </div>

                                            <div class="col-md-12" style="width:100%">
                                                @livewire('forms.form', [
                                                'type' => 'select-dropdown',
                                                'form_elem' => 'layout',
                                                'placeHolder' =>  __('cytoscape/index.select_layout'),
                                                'associated_list' => ['fcose' => 'fcose', 'breadthfirst' => 'breadthfirst', 'dagre' => 'dagre', 'avsdf' => 'avsdf', 'cise' => 'cise'],
                                                'showError' => false,
                                                ])
                                            </div>

                                            <div class="col-md-12" style="width:100%">
                                                @livewire('forms.form', [
                                                'type' => 'select-dropdown',
                                                'form_elem' => 'list',
                                                'placeHolder' => __('cytoscape/index.select_list'),
                                                'associated_list' => $lists_name,
                                                'showError' => false,
                                                ])
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6 p-2">
                                        <div class="row">
                                            <div class="col-12">
                                                <h3>Filters</h3>
                                            </div>
                                            <div class="col-md-12" style="width:100%">
                                                @livewire('forms.form', [
                                                'type' => 'select-dropdown',
                                                'form_elem' => 'from',
                                                'placeHolder' => __('cytoscape/index.select_first_item'),
                                                'associated_list' => $refugees,
                                                'showError' => false,
                                                ])
                                            </div>
                                            <div class="col-md-12" style="width:100%">
                                                @livewire('forms.form', [
                                                'type' => 'select-dropdown',
                                                'form_elem' => 'to',
                                                'placeHolder' => __('cytoscape/index.select_second_item'),
                                                'associated_list' => $refugees,
                                                'showError' => false,
                                                ])
                                            </div>
                                            <div class="col-md-12" style="width:100%">
                                                @livewire('forms.form', [
                                                'type' => 'select-dropdown',
                                                'form_elem' => 'relations',
                                                'placeHolder' => __('cytoscape/index.select_type_of_relation'),
                                                'associated_list' => ['Travelled with' => 'Travelled with', 'Saw' => 'Saw', 'Non-biological relationship' => 'Non-biological relationship', 'Service' => 'Service', 'Biological relationship' => 'Biological relationship'],
                                                'showError' => false,
                                                ])
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-3 col-lg-4 col-md-12 ">
                                <div class="row">
                                    <div class="col-12">
                                        <h3>{{ __('cytoscape/index.actions') }}</h3>
                                    </div>
                                    <div class="col-12">
                                        <button class="bg-red-200 hover:bg-red-300 text-black hover:text-black font-bold py-2 px-4 rounded mb-2" id="clear">
                                            {{ __('cytoscape/index.clear') }}
                                        </button>

                                        <button class="bg-blue-200 hover:bg-blue-300 text-black hover:text-black font-bold py-2 px-4 rounded mb-2"
                                                id="betweenness_centrality">
                                            {{ __('cytoscape/index.show_centrality') }}
                                        </button>

                                        <button id="save" class="bg-green-200 hover:bg-green-300 text-black hover:text-black font-bold py-2 px-4 rounded mb-2">
                                            {{ __('cytoscape/index.save_as_png') }}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

            </div>
        </div>
        <div class="ml-5 mr-5">
            <div id="cyWrapper"  style="border: 1px solid lightgrey;">
                <div id="cy" class="h5/6"></div>
            </div>
        </div>
        <script>
            var persons = {!!  $persons!!};
            var field_list = {!! $field_list !!};
        </script>
    </div>
</x-app-layout>
