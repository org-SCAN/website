export const layout_content = {
        name: 'cise',

            animate: "end",
            // Animation duration used for animate:'end'

            animationDuration: 1000,
            // Easing for animate:'end'
            animationEasing: 'ease-in-out',
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
    }
