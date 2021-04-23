import cytoscape from 'cytoscape';
import dagre from 'cytoscape-dagre';
import $ from "jquery";

cytoscape.use( dagre );

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
                        'line-color': '#ff0000',
                        'target-arrow-color': '#ff0000'
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
                }
            ],
        })
        var defaults = {
            // dagre algo options, uses default value on undefined
            nodeSep: undefined, // the separation between adjacent nodes in the same rank
            edgeSep: undefined, // the separation between adjacent edges in the same rank
            rankSep: undefined, // the separation between each rank in the layout
            rankDir: undefined, // 'TB' for top to bottom flow, 'LR' for left to right,
            ranker: undefined, // Type of algorithm to assign a rank to each node in the input graph. Possible values: 'network-simplex', 'tight-tree' or 'longest-path'
            minLen: function (edge) {
                return 1;
            }, // number of ranks to keep between the source and target of the edge
            edgeWeight: function (edge) {
                return 1;
            }, // higher weight edges are generally made shorter and straighter than lower weight edges

            // general layout options
            fit: true, // whether to fit to viewport
            padding: 100, // fit padding
            spacingFactor: 100, // Applies a multiplicative factor (>0) to expand or compress the overall area that the nodes take up
            nodeDimensionsIncludeLabels: false, // whether labels should be included in determining the space used by a node
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
            } // on layoutstop
        };

        cy.layout({
            name: "dagre",
            defaults
        }).run();

    });
}

drawGraph();

