import cytoscape from 'cytoscape';
import dagre from 'cytoscape-dagre';
import cise from 'cytoscape-cise';
import fcose from 'cytoscape-fcose';
import $ from "jquery";

cytoscape.use( dagre );
cytoscape.use( cise );
cytoscape.use( fcose );

function testJson(){
    $.getJSON('/content.json', function(data){
        console.log(data)
    });
}
function drawGraph(){
    $.getJSON('/content.json', function(data){
        var cy = cytoscape({

            container: document.getElementById('cy'), // container to render in
            elements: data,
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

        const urlParams = new URLSearchParams(window.location.search);
        const layout_name = (urlParams.has("layout") ? urlParams.get("layout") : "dagre");

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
                spacingFactor: 1, // Applies a multiplicative factor (>0) to expand or compress the overall area that the nodes take up
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
                nodeSeparation: 12.5,

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
                animate: true,
                // Duration of animation in ms, if enabled
                animationDuration: 1000,
                // Easing of animation, if enabled
                animationEasing: undefined,
                // Fit the viewport to the repositioned nodes
                fit: true,
                // Padding around layout
                padding: 30,
                // Whether to include labels in node dimensions. Valid in "proof" quality
                nodeDimensionsIncludeLabels: false,
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
                nodeSeparation: 75,
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




        console.log("Toto 2\n");
        /*
        var dfs = cy.elements().aStar({
            root: '#114e00d8-f14b-379a-b720-08fb9d87b47f',
            goal: '#dbfd8e31-a7ac-3737-9ab8-593a9fa227c0',
            directed: false
        })
        dfs.path.select();
    */


        var dijkstra = cy.elements().dijkstra('#14a826b6-e0d5-393a-8093-2dc2ee6a7197',function(edge){
            return edge.data('weight');
        },false);
        var bfs = dijkstra.pathTo( cy.$('#88fbc82c-1455-3171-abf4-ce2d40dd1e09'));

        var x=0;
        var highlightNextEle = function(){
            var el=bfs[x];
            el.addClass('highlighted');
            if(x<bfs.length){
                x++;
                setTimeout(highlightNextEle);
            }
        };
        highlightNextEle();

        cy.unbind('click')
        cy.bind('click', 'node', function(event) {
            // .union() takes two collections and adds both together without duplicates
            var connected = event.target
            console.log(connected);
            connected = connected.union(event.target.component())

            // in one line:
            // event.target.union(event.target.predecessors().union(event.target.successors()))

            // .not() filters out whatever is not specified in connected, e.g. every other node/edge not present in connected
            var notConnected = cy.elements().not(connected)

            // if you want, you can later add the saved elements again
            var saved = cy.remove(notConnected)
        });

    });
}

drawGraph();

