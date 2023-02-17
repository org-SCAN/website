export const layout_content = {
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
    animate: true, // whether to transition the node positions
    animationDuration: 1000, // duration of animation in ms if enabled
    animationEasing: 'ease-in-out', // easing of animation if enabled,
    animateFilter: function ( node, i ){ return true; }, // a function that determines whether the node should be animated.  All nodes animated by default on animate enabled.  Non-animated nodes are positioned immediately when the layout starts
    ready: undefined, // callback on layoutready
    stop: undefined, // callback on layoutstop
    transform: function (node, position ){ return position; } // transform a given node position. Useful for changing flow direction in discrete layouts
}