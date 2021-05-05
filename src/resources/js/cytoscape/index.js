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
                        'target-arrow-color': '#ff0000'
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

        console.log("Toto \n");
        /*
        var dfs = cy.elements().aStar({
            root: '#114e00d8-f14b-379a-b720-08fb9d87b47f',
            goal: '#dbfd8e31-a7ac-3737-9ab8-593a9fa227c0',
            directed: false
        })
        dfs.path.select();
    */

        var dijkstra = cy.elements().dijkstra('#618c9b31-d5af-3b56-a488-f6357370a92d',function(edge){
            return edge.data('weight');
        },false);
        var bfs = dijkstra.pathTo( cy.$('#dbfd8e31-a7ac-3737-9ab8-593a9fa227c0'));

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
            /*
            connected = connected.union(event.target.predecessors())
            connected = connected.union(connected.successors())*/
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

