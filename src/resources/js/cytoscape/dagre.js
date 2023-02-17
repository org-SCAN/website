export const layout_content = {
        name: 'dagre',
        nodeSep: 25, // the separation between adjacent nodes in the same rank
        edgeSep: 10, // the separation between adjacent edges in the same rank
        rankSep: 25, // the separation between each rank in the layout
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
        animate: true, // whether to transition the node positions
        animateFilter: function (node, i) {
            return true;
        }, // whether to animate specific nodes when animation is on; non-animated nodes immediately go to their final positions
        animationDuration: 1000, // duration of animation in ms if enabled
        animationEasing: 'ease-in-out', // easing of animation if enabled
        boundingBox: undefined, // constrain layout bounds; { x1, y1, x2, y2 } or { x1, y1, w, h }
        transform: function (node, pos) {
        return pos;
        }, // a function that applies a transform to the final node position
        ready: function () {
        }, // on layoutready
        stop: function () {
        } // on layoutston
    }