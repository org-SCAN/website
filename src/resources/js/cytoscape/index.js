import cytoscape from 'cytoscape';
import dagre from 'cytoscape-dagre';
import cise from 'cytoscape-cise';
import fcose from 'cytoscape-fcose';
import cxtmenu from 'cytoscape-cxtmenu';
import $ from "jquery";

cytoscape.use( dagre );
cytoscape.use( cise );
cytoscape.use( fcose );
cytoscape.use( cxtmenu );

function perc2color(perc) {
    var r, g, b = 0;
    if(perc > 50) {
        r = 255;
        g = Math.round(5.1 * (100-perc));
    }
    else {
        g = 255;
        r = Math.round(510 - 5.10 * (100-perc));
    }
    var h = r * 0x10000 + g * 0x100 + b * 0x1;
    return '#' + ('000000' + h.toString(16)).slice(-6);
}

function view_relative(cy, ele){
    var connected = ele

    connected = connected.union(ele.component())
    var notConnected = cy.elements().not(connected)
    var saved = cy.remove(notConnected)
}
function insertParam(key, value) {
    key = encodeURIComponent(key);
    value = encodeURIComponent(value);

    // kvp looks like ['key1=value1', 'key2=value2', ...]
    var kvp = document.location.search.substr(1).split('&');
    let i=0;

    for(; i<kvp.length; i++){
        if (kvp[i].startsWith(key + '=')) {
            let pair = kvp[i].split('=');
            pair[1] = value;
            kvp[i] = pair.join('=');
            break;
        }
    }

    if(i >= kvp.length){
        kvp[kvp.length] = [key,value].join('=');
    }

    // can return this or...
    let params = kvp.join('&');

    // reload page with new params
    document.location.search = params;
}
function drawGraph(){
    $.getJSON('/content.json', function(data){
        const urlParams = new URLSearchParams(window.location.search);
        console.log(data);
        var cy = cytoscape({

            container: document.getElementById('cy'), // container to render in
            elements: data,
            style: [ // the stylesheet for the graph

                {
                    selector: 'node',
                    style: {
                        'background-color': '#666',
                        'label': 'data(name)',
                        'font-size': 25
                    }
                },
                {
                    selector: 'node[role = "deceased"]',
                    style: {
                        'background-color': '#000000',
                        'label': 'data(name)',
                        'role': 'data(role)',
                        'font-size': 25,
                        'shape': 'diamond'
                    }
                },
                {
                    selector: 'node[role = "informant"]',
                    style: {
                        'background-color': '#f89f9f',
                        'label': 'data(name)',
                        'role': 'data(role)',
                        'font-size': 25
                    }
                },
                {
                    selector: 'node[role = "possibly sought"]',
                    style: {
                        'background-color': '#d995e5',
                        'label': 'data(name)',
                        'role': 'data(role)',
                        'font-size': 25
                    }
                },
                {
                    selector: 'node[role = "relative"]',
                    style: {
                        'background-color': '#7773fc',
                        'label': 'data(name)',
                        'role': 'data(role)',
                        'font-size': 25
                    }
                },
                {
                    selector: 'node[role = "survivor"]',
                    style: {
                        'background-color': '#1dfc00',
                        'label': 'data(name)',
                        'role': 'data(role)',
                        'font-size': 25,
                        'shape': 'star'
                    }
                },
                {
                    selector: 'node[role = "witness"]',
                    style: {
                        'background-color': '#9f9d9d',
                        'label': 'data(name)',
                        'role': 'data(role)',
                        'font-size': 25
                    }
                },
                {
                    selector: 'node[role = "sought"]',
                    style: {
                        'background-color': '#ff0000',
                        'label': 'data(name)',
                        'role': 'data(role)',
                        'font-size': 25,
                        'shape': 'triangle'
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
                },
                {
                    selector: 'edge[label = "Saw"]',
                    style: {
                        'line-color': '#734d39',
                        'target-arrow-color': '#734d39'
                    }
                },
                {
                    selector: 'edge[label = "Biological relationship"]',
                    style: {
                        'line-color': '#0099ff',
                        'target-arrow-color': '#0099ff'
                    }
                },
                {
                    selector: 'edge[label = "Travelled with"]',
                    style: {
                        'line-color': '#00ff2a',
                        'target-arrow-color': '#00ff2a'
                    }
                },
                {
                    selector: 'edge[label = "Service"]',
                    style: {
                        'line-color': '#7e00ff',
                        'target-arrow-color': '#7e00ff'
                    }
                },
                {
                    selector: 'edge[label = "Non-biological relationship"]',
                    style: {
                        'line-color': '#cbaf1d',
                        'target-arrow-color': '#cbaf1d'
                    }
                },
                {
                    selector: '.highlighted',
                    style: {
                        'background-color': '#ff0000',
                        'line-color': '#ff0000',
                        'target-arrow-color': '#ff0000',
                        'label': 'data(label)'
                    }
                }
            ],
        })


        const layout_name = (urlParams.has("layout") ? urlParams.get("layout") : "dagre");

        /*

            ******** DEFINE LAYOUT ******

         */
        if(layout_name == "dagre") {
            cy.layout({
                name: layout_name,
                // dagre algo options, uses default value on undefined
                nodeSep: 30, // the separation between adjacent nodes in the same rank
                edgeSep: 10, // the separation between adjacent edges in the same rank
                rankSep: 30, // the separation between each rank in the layout
                rankDir: "TB", // 'TB' for top to bottom flow, 'LR' for left to right,
                ranker: "longest-path", // Type of algorithm to assign a rank to each node in the input graph. Possible values: 'network-simplex', 'tight-tree' or 'longest-path'
                minLen: function (edge) {
                    return 1;
                }, // number of ranks to keep between the source and target of the edge
                edgeWeight: function (edge) {
                    return 1;
                }, // higher weight edges are generally made shorter and straighter than lower weight edges

                // general layout options
                fit: true, // whether to fit to viewport
                padding: 50, // fit padding
                spacingFactor: 1.5, // Applies a multiplicative factor (>0) to expand or compress the overall area that the nodes take up
                nodeDimensionsIncludeLabels: true, // whether labels should be included in determining the space used by a node
                animate: false, // whether to transition the node positions
                animateFilter: function (node, i) {
                    return true;
                }, // whether to animate specific nodes when animation is on; non-animated nodes immediately go to their final positions
                animationDuration: 500, // duration of animation in ms if enabled
                animationEasing: undefined, // easing of animation if enabled
                boundingBox: undefined, // constrain layout bounds; { x1, y1, x2, y2 } or { x1, y1, w, h }
                transform: function (node, pos) {
                    return pos;
                }, // a function that applies a transform to the final node position
                ready: function () {
                }, // on layoutready
                stop: function () {
                } // on layoutston
            }).run();
        }
        if(layout_name == "cise") {
            cy.layout({
                name: layout_name,
                // ClusterInfo can be a 2D array contaning node id's or a function that returns cluster ids.
                // For the 2D array option, the index of the array indicates the cluster ID for all elements in
                // the collection at that index. Unclustered nodes must NOT be present in this array of clusters.
                //
                // For the function, it would be given a Cytoscape node and it is expected to return a cluster id
                // corresponding to that node. Returning negative numbers, null or undefined is fine for unclustered
                // nodes.
                // e.g
                // Array:                                     OR          function(node){
                //  [ ['n1','n2','n3'],                                       ...
                //    ['n5','n6']                                         }
                //    ['n7', 'n8', 'n9', 'n10'] ]
                ///clusters: clusterInfo,

                // -------- Optional parameters --------
                // Whether to animate the layout
                // - true : Animate while the layout is running
                // - false : Just show the end result
                // - 'end' : Animate directly to the end result
                animate: false,

                // number of ticks per frame; higher is faster but more jerky
                refresh: 10,

                // Animation duration used for animate:'end'
                animationDuration: undefined,

                // Easing for animate:'end'
                animationEasing: undefined,

                // Whether to fit the viewport to the repositioned graph
                // true : Fits at end of layout for animate:false or animate:'end'
                fit: true,

                // Padding in rendered co-ordinates around the layout
                padding: 30,

                // separation amount between nodes in a cluster
                // note: increasing this amount will also increase the simulation time
                nodeSeparation: 20,

                // Inter-cluster edge length factor
                // (2.0 means inter-cluster edges should be twice as long as intra-cluster edges)
                idealInterClusterEdgeLengthCoefficient: 1.4,

                // Whether to pull on-circle nodes inside of the circle
                allowNodesInsideCircle: false,

                // Max percentage of the nodes in a circle that can move inside the circle
                maxRatioOfNodesInsideCircle: 0.1,

                // - Lower values give looser springs
                // - Higher values give tighter springs
                springCoeff: 0.45,

                // Node repulsion (non overlapping) multiplier
                nodeRepulsion: 4500,

                // Gravity force (constant)
                gravity: 0.25,

                // Gravity range (constant)
                gravityRange: 3.8,

                // Layout event callbacks; equivalent to `layout.one('layoutready', callback)` for example
                ready: function(){}, // on layoutready
                stop: function(){}, // on layoutstop
            }).run();
        }
        if(layout_name == "fcose") {
            cy.layout({
                name: layout_name,
                // 'draft', 'default' or 'proof'
                // - "draft" only applies spectral layout
                // - "default" improves the quality with incremental layout (fast cooling rate)
                // - "proof" improves the quality with incremental layout (slow cooling rate)
                quality: "default",
                // Use random node positions at beginning of layout
                // if this is set to false, then quality option must be "proof"
                randomize: true,
                // Whether or not to animate the layout
                animate: false,
                // Duration of animation in ms, if enabled
                animationDuration: 1000,
                // Easing of animation, if enabled
                animationEasing: undefined,
                // Fit the viewport to the repositioned nodes
                fit: true,
                // Padding around layout
                padding: 30,
                // Whether to include labels in node dimensions. Valid in "proof" quality
                nodeDimensionsIncludeLabels: true,
                // Whether or not simple nodes (non-compound nodes) are of uniform dimensions
                uniformNodeDimensions: false,
                // Whether to pack disconnected components - valid only if randomize: true
                packComponents: true,
                // Layout step - all, transformed, enforced, cose - for debug purpose only
                step: "all",

                /* spectral layout options */

                // False for random, true for greedy sampling
                samplingType: true,
                // Sample size to construct distance matrix
                sampleSize: 25,
                // Separation amount between nodes
                nodeSeparation: 100,
                // Power iteration tolerance
                piTol: 0.0000001,

                /* incremental layout options */

                // Node repulsion (non overlapping) multiplier
                nodeRepulsion: node => 4500,
                // Ideal edge (non nested) length
                idealEdgeLength: edge => 50,
                // Divisor to compute edge forces
                edgeElasticity: edge => 0.45,
                // Nesting factor (multiplier) to compute ideal edge length for nested edges
                nestingFactor: 0.1,
                // Maximum number of iterations to perform
                numIter: 2500,
                // For enabling tiling
                tile: true,
                // Represents the amount of the vertical space to put between the zero degree members during the tiling operation(can also be a function)
                tilingPaddingVertical: 10,
                // Represents the amount of the horizontal space to put between the zero degree members during the tiling operation(can also be a function)
                tilingPaddingHorizontal: 10,
                // Gravity force (constant)
                gravity: 0.25,
                // Gravity range (constant) for compounds
                gravityRangeCompound: 1.5,
                // Gravity force (constant) for compounds
                gravityCompound: 1.0,
                // Gravity range (constant)
                gravityRange: 3.8,
                // Initial cooling factor for incremental layout
                initialEnergyOnIncremental: 0.3,

                /* constraint options */

                // Fix desired nodes to predefined positions
                // [{nodeId: 'n1', position: {x: 100, y: 200}}, {...}]
                fixedNodeConstraint: undefined,
                // Align desired nodes in vertical/horizontal direction
                // {vertical: [['n1', 'n2'], [...]], horizontal: [['n2', 'n4'], [...]]}
                alignmentConstraint: undefined,
                // Place two nodes relatively in vertical/horizontal direction
                // [{top: 'n1', bottom: 'n2', gap: 100}, {left: 'n3', right: 'n4', gap: 75}, {...}]
                relativePlacementConstraint: undefined,

                /* layout event callbacks */
                ready: () => {}, // on layoutready
                stop: () => {} // on layoutstop
            }).run();
        }
        if(layout_name == "breadthfirst") {
            cy.layout({
                    name: 'breadthfirst',
                    fit: true, // whether to fit the viewport to the graph
                    directed: false, // whether the tree is directed downwards (or edges can point in any direction if false)
                    padding: 30, // padding on fit
                    circle: false, // put depths in concentric circles if true, put depths top down if false
                    grid: false, // whether to create an even grid into which the DAG is placed (circle:false only)
                    spacingFactor: 1, // positive spacing factor, larger => more space between nodes (N.B. n/a if causes overlap)
                    boundingBox: undefined, // constrain layout bounds; { x1, y1, x2, y2 } or { x1, y1, w, h }
                    avoidOverlap: true, // prevents node overlap, may overflow boundingBox if not enough space
                    nodeDimensionsIncludeLabels: true, // Excludes the label when calculating node bounding boxes for the layout algorithm
                    roots: undefined, // the roots of the trees
                    maximal: true, // whether to shift nodes down their natural BFS depths in order to avoid upwards edges (DAGS only)
                    animate: false, // whether to transition the node positions
                    animationDuration: 500, // duration of animation in ms if enabled
                    animationEasing: undefined, // easing of animation if enabled,
                    animateFilter: function ( node, i ){ return true; }, // a function that determines whether the node should be animated.  All nodes animated by default on animate enabled.  Non-animated nodes are positioned immediately when the layout starts
                    ready: undefined, // callback on layoutready
                    stop: undefined, // callback on layoutstop
                    transform: function (node, position ){ return position; } // transform a given node position. Useful for changing flow direction in discrete layouts
            }).run();
        }


        /*

            **** DEFINE MENU ******

         */
        console.log("Param menu :")

        let defaults = {
            menuRadius: function(ele){ return 100; }, // the outer radius (node center to the end of the menu) in pixels. It is added to the rendered size of the node. Can either be a number or function as in the example.
            selector: 'node', // elements matching this Cytoscape.js selector will trigger cxtmenus
            commands: [ // an array of commands to list in the menu or a function that returns the array

                {
                    content: 'Set as FROM',
                    select: function(ele){
                        insertParam("from", ele.id())
                    }
                },
                {
                    content: 'set as TO',
                    select: function(ele){
                        insertParam("to", ele.id())
                    }
                },
                {
                    content: 'View information',
                    select: function(ele){
                        window.location.replace("/person/" + ele.id());
                    }
                },

                {
                    content: 'View related persons',
                    select: function(ele){
                        view_relative(cy, ele)
                    }
                },

                {
                    content: 'Save position',
                    select: function(ele){
                        console.log( ele.position() );
                    },
                    enabled : false
                }
            ], // function( ele ){ return [ /*...*/ ] }, // a function that returns commands or a promise of commands
            fillColor: 'rgba(0, 0, 0, 0.75)', // the background colour of the menu
            activeFillColor: 'rgba(1, 105, 217, 0.75)', // the colour used to indicate the selected command
            activePadding: 20, // additional size in pixels for the active command
            indicatorSize: 24, // the size in pixels of the pointer to the active command, will default to the node size if the node size is smaller than the indicator size,
            separatorWidth: 3, // the empty spacing in pixels between successive commands
            spotlightPadding: 4, // extra spacing in pixels between the element and the spotlight
            adaptativeNodeSpotlightRadius: false, // specify whether the spotlight radius should adapt to the node size
            minSpotlightRadius: 24, // the minimum radius in pixels of the spotlight (ignored for the node if adaptativeNodeSpotlightRadius is enabled but still used for the edge & background)
            maxSpotlightRadius: 38, // the maximum radius in pixels of the spotlight (ignored for the node if adaptativeNodeSpotlightRadius is enabled but still used for the edge & background)
            openMenuEvents: 'cxttapstart taphold', // space-separated cytoscape events that will open the menu; only `cxttapstart` and/or `taphold` work here
            itemColor: 'white', // the colour of text in the command's content
            itemTextShadowColor: 'transparent', // the text shadow colour of the command's content
            zIndex: 9999, // the z-index of the ui div
            atMouse: false, // draw menu at mouse position
            outsideMenuCancel: false // if set to a number, this will cancel the command if the pointer is released outside of the spotlight, padded by the number given
        };

        cy.cxtmenu( defaults );

        cy.cxtmenu({
            selector: 'core',

            commands: [
                {
                    content: 'Dagre layout',
                    select: function(){
                        insertParam("layout", "dagre")
                    }
                },

                {
                    content: 'Cise layout',
                    select: function(){
                        insertParam("layout", "cise")
                    }
                },
                {
                    content: 'Fcose layout',
                    select: function(){
                        insertParam("layout", "fcose")
                    }
                },
                {
                    content: 'Breadthfirst layout',
                    select: function(){
                        insertParam("layout", "breadthfirst")
                    }
                },
                {
                    content: 'Show relation details',
                    select: function(){
                        cy.style().selector("edge").style('label','data(detail)').update()
                    }
                },
                {
                    content: 'Hide relation details',
                    select: function(){
                        cy.style().selector("edge").style('label','').update()
                    }
                }
            ]
        });

        cy.cxtmenu({
            selector: 'edge',

            commands: [
                {
                    content: 'Show detail',
                    select: function(ele){
                        ele.style("label", ele.data("detail"))
                    }
                },
                {
                    content: 'Hide detail',
                    select: function(ele){
                        ele.removeStyle( "label" )
                    }
                },

                {
                    content: 'Edit relation',
                    select: function(ele){
                        window.location.replace("/links/"+ele.id()+"/edit");
                    }
                }
            ]
        });

        /*

                **** DEFINE persons details ****

         */
        const from = (urlParams.has("from") ? urlParams.get("from") : "");
        if(from != ""){
            view_relative(cy, cy.$id(from))
        }
        /*

                **** DEFINE CALCULATION ****

         */


        const calcul = (urlParams.has("calcul") ? urlParams.get("calcul") : "");

        if(calcul == "betweennessCentrality"){

            let bcn = cy.elements().bc();

            cy.nodes().forEach( n => {
                n.data("bcn", bcn.betweennessNormalized( n ));
                n.style("background-color",perc2color(100*n.data("bcn")))
            } );

        }
        const to = (urlParams.has("to") ? urlParams.get("to") : "");

        if(from != "" && to != "") {
            var dijkstra = cy.elements().dijkstra('#'+from, function (edge) {
                return edge.data('weight');
            }, false);
            var bfs = dijkstra.pathTo(cy.$('#'+to));

            var x = 0;
            var highlightNextEle = function () {
                var el = bfs[x];
                el.addClass('highlighted');
                if (x < bfs.length) {
                    x++;
                    setTimeout(highlightNextEle);
                }
            };
            highlightNextEle();
        }

    });


}

drawGraph();

