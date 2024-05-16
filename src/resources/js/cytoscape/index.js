import cytoscape from 'cytoscape';
import dagre from 'cytoscape-dagre';
import cise from 'cytoscape-cise';
import fcose from 'cytoscape-fcose';
import avsdf from 'cytoscape-avsdf';
import cxtmenu from 'cytoscape-cxtmenu';
import $ from "jquery";

import {layout_content as dagre_layout} from "./dagre.js";
import {layout_content as cise_layout} from "./cise.js";
import {layout_content as fcose_layout} from "./fcose.js";
import {layout_content as breadthfirst_layout} from "./breadthfirst.js";
import {layout_content as avsdf_layout} from "./avsdf.js";


cytoscape.use( dagre );
cytoscape.use( cise );
cytoscape.use( fcose );
cytoscape.use( avsdf );
cytoscape.use( cxtmenu );


const API_key = process.env.MIX_SCAN_API_TOKEN;
const API_application_id = 'SCAN_CYTOSCAPE';

var listRelations = [];
var lists = getLists()

// toggle elements
var relation_details = false;
var betweenness_activate = false;

// variable relative to the list of node type
var listHasChanged = false;
var listFieldId = "";
var listNodeTypeId = "";
var listNodeType = [];
var list_node_type_colors = {}

listRelations = getListRelations();


/**
 * This function calls the API on the given URL.
 */

function callAPI(url) {
    let response;
    $.ajax({
        url: url,
        method: 'GET',
        dataType: 'json',
        headers: {
            "Accept": "application/json",
            "Authorization": "Bearer "+API_key,
            'Application-id': API_application_id
        },
        async: false,
        success: function (data) {
            response = data;
        },
        error: function (data) {
            console.log(data);
        }
    });
    return response;

}

/**
 * This function calls the API to get the list of relations
 */
function getLists(){
    // define API key
    return callAPI('/api/lists');
}

/**
 * This function get the displayed value field based on the list id
 */
function getDisplayedValue(list_id){
    let displayedValue;
    lists.forEach(function (list) {
        if(list.id === list_id){
             return displayedValue = list.displayed_value;
        }
    });
    return displayedValue;
}

/**
 * This function get the list id from the name of the list.
 */
function getListId(listName){
    let listId;
    lists.forEach(function (list) {
        if(list.name === listName){
            listId = list.id;
        }
    });
    return listId;
}

/**
 * This function calls the API to get the list of relations.
 * It gets the relation list id from the lists array.
 */
function getListRelations(){
    return callAPI('/api/list/'+getListId('ListRelation'));
}
/**
 * This function calls the API to get the elem of the selected list.
 */
function getListNodeType(list_id){
    return callAPI('/api/list/'+list_id);
}

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
    cy.elements().show();
    // check that ele is a node
    if(ele.isNode()){
        // show all elements
        let connected = ele.union(ele.component())
        let notConnected = cy.elements().not(connected)
        // hide not connected nodes
        notConnected.hide();
    }
    // get current shown elements
    let shown = cy.elements().filter(':visible');
    cy.fit(shown, 50)
}

function drawGraph(){
    $.getJSON('/content.json', function(data){

        const urlParams = new URLSearchParams(window.location.search);
        var style = [];
        // add elem to the style array
        style.push({
            selector: 'node',
            style: {
                'background-color': '#666',
                'label': 'data(name)',
                'font-size': 25
            }
        })

        style.push({
            selector: 'edge',
            style: {
                'width': 3,
                'line-color': '#ccc',
                'target-arrow-color': '#ccc',
                'target-arrow-shape': 'triangle',
                'curve-style': 'bezier'
            }
        });

        listRelations.forEach(function (relation) {
            style.push({
                selector: 'edge[label = "'+relation.name.eng+'"]',
                style: {
                    'line-color': relation.color,
                    'target-arrow-color': relation.color,
                    'source-arrow-color': relation.color,
                }
            });
        });

        style.push({
            selector: 'edge[type = "bilateral"]',
            style: {
                'source-arrow-shape':  'triangle',
                'width': 4,
            }
        });

        style.push({
            selector: '.highlighted',
            style: {
                'background-color': '#ff0000',
                'line-color': '#ff0000',
                'target-arrow-color': '#ff0000',
                'source-arrow-color': '#ff0000',
                'label': 'data(label)'
            }
        });

        var cy = cytoscape({
            container: document.getElementById('cy'), // container to render in
            elements: data,
            style: style
        })

        /*
            ******** DEFINE LAYOUT ******
         */
        cy.layout(dagre_layout).run();
        // set the selected layout to dagre in the select and display it as selected
        changeSelectedLayout('dagre');

        /*

            **** DEFINE MENU ******
        */

        let defaults = {
            menuRadius: function(ele){ return 100; }, // the outer radius (node center to the end of the menu) in pixels. It is added to the rendered size of the node. Can either be a number or function as in the example.
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
            outsideMenuCancel: false, // if set to a number, this will cancel the command if the pointer is released outside of the spotlight, padded by the number given
            selector: 'node', // elements matching this Cytoscape.js selector will trigger cxtmenus
            commands: [ // an array of commands to list in the menu or a function that returns the array
                {
                    content: 'Set as FROM',
                    select: function(ele){
                        // set the from selected value
                        $('#from').val(ele.id());
                        $('#select2-from-container').attr('title', ele.json()["data"]["name"]).text(ele.json()["data"]["name"]);
                        setFrom();
                    }
                },
                {
                    content: 'set as TO',
                    select: function(ele){
                        $('#to').val(ele.id());
                        $('#select2-to-container').attr('title', ele.json()["data"]["name"]).text(ele.json()["data"]["name"]);
                        setTo()
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
            ] // function( ele ){ return [ /*...*/ ] }, // a function that returns commands or a promise of commands
        };
        cy.cxtmenu( defaults );
        cy.cxtmenu({
            selector: 'core',

            commands: [
                {
                    content: 'Dagre layout',
                    select: function(){
                        changeSelectedLayout('dagre');
                        cy.layout(dagre_layout).run();
                    }
                },

                {
                    content: 'Cise layout',
                    select: function(){
                        changeSelectedLayout('cise');
                        cy.layout(cise_layout).run();
                    }
                },
                {
                    content: 'Fcose layout',
                    select: function(){
                        changeSelectedLayout('fcose');
                        cy.layout(fcose_layout).run();
                    }
                },
                {
                    content: 'Breadthfirst layout',
                    select: function(){

                        changeSelectedLayout('breadthfirst');
                        cy.layout(breadthfirst_layout).run();
                    }
                },
                {
                    content: 'AVSDF layout',
                    select: function(){
                        // set the selected layout to avsdf in the select

                        changeSelectedLayout('avsdf');
                        cy.layout(avsdf_layout).run();
                    }
                },
                {
                    content: 'Relation details',
                    select: function(){
                        relation_details = !relation_details;
                        if (relation_details) {
                            cy.style().selector('edge').style('label', 'data(label)').update();
                        }else{
                            cy.style().selector("edge").style('label','').update()
                        }
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




        // when a person is selected in the from list, we show the related persons
        window.$("#from").change(function() {
            setFrom()
            // close the dropdown
            $(this).blur();
        });

        // when a person is selected in the to list, we show the related persons, if the from is not empty
        // else we show the shortest path between the two persons
        window.$("#to").change(function() {

            setTo()
            //close the dropdown
            $(this).blur();
        });

        // On layout dropdown change, we change the layout accordingly
        window.$("#layout").change(function() {
            let layout = $(this).val();

            if(layout == "dagre"){
                changeSelectedLayout('dagre');
                cy.layout(dagre_layout).run();
            }
            else if(layout == "cise"){
                changeSelectedLayout('cise');
                cy.layout(cise_layout).run();
            }
            else if(layout == "fcose"){
                changeSelectedLayout('fcose');
                cy.layout(fcose_layout).run();
            }
            else if(layout == "breadthfirst"){
                changeSelectedLayout('breadthfirst');
                cy.layout(breadthfirst_layout).run();
            }
            else if(layout == "avsdf"){
                changeSelectedLayout('avsdf');
                cy.layout(avsdf_layout).run();
            }

            $(this).blur();
        }) ;

        // On relations dropdown change, we change the layout accordingly
        window.$("#relations").change(function() {
            let relation = $(this).val();
            if (relation) {
                cy.filter('edge[label = "' + relation + '"]').hide();
                cy.filter('edge[label != "' + relation + '"]').show();
            }
            $(this).blur();
        });

        // On layout dropdown change, we change the layout accordingly
        window.$("#list").change(function() {
            if($(this).val() == "") {
                clear()
                return ;
            }
            listHasChanged = true;

            listFieldId = $(this).val(); // contains the field id
            listNodeTypeId = window.field_list[listFieldId]; // contains the list id

            // get the list content from the api
            listNodeType = getListNodeType(listNodeTypeId) // contains the content of the list

            // update the data : add the type attribute to the nodes according to the list
            updateData()

            // update the style : add the color attribute to the nodes according to the list
            updateStyle()

            // update the legend : add the legend according to the list
            updateLegend()

            listHasChanged = false;
            $(this).blur();
        }) ;

        // on click on the save button, save the graph as png
        $("#save").click(function(){
            var png64 = cy.png();
            var a = document.createElement('a');
            a.href = png64;
            // the filename is the name of the graph + the date
            a.download = "graph" + "_" + new Date().toISOString().slice(0, 10) + ".png";
            a.click();
        });

        // on click on the betweenness centrality button, calculate the betweenness centrality
        $("#betweenness_centrality").click(function() {
            betweenness_activate = !betweenness_activate;
            if(betweenness_activate){
                let bcn = cy.elements().bc();
                cy.nodes().forEach(n => {
                    n.data("bcn", bcn.betweennessNormalized(n));
                    n.style("background-color", perc2color(100 * n.data("bcn")))
                });
                // Change the button text to hide the betweenness centrality
                $("#betweenness_centrality").text("Hide centrality");
            }
            else{ // if the betweenness centrality is already displayed, hide it
                cy.nodes().forEach(n => {
                   // remove the background color
                     n.removeStyle("background-color");
                });
                updateStyle()
                // Change the button text to show the betweenness centrality
                $("#betweenness_centrality").text("Show centrality");
            }
        });

        // on click on the clear button, redraw the graph
        $("#clear").click(function(){
            clear()
        });

        function setFrom(){
            let from = $("#from").val();
            let to = $("#to").val();

            if(from == ""){
                clearDijkstra()
            }
            else if(to != ""){
                computeDijkstra(from, to);
            }
            view_relative(cy, cy.$id(from) ?? [])
        }
        function setTo(){
            let from = $("#from").val();
            let to = $("#to").val();

            if(to == ""){
                clearDijkstra()
            }
            else if(from != ""){
                computeDijkstra(from, to);
            }
            else{
                view_relative(cy, cy.$id(to) ?? [])
            }
        }
        function changeSelectedLayout(layout) {
            $('#layout').val(layout);
            $('#select2-layout-container').attr('title', layout).text(layout);
        }
        function computeDijkstra(from, to) {
            clearDijkstra()
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
        function clearDijkstra() {
            cy.elements().removeClass('highlighted');
        }
        function generateNodeStyle(){
            let nodeStyle = [];
            let displayed = getDisplayedValue(listNodeTypeId);
            // 3. Generate the style
            listNodeType.forEach(function(nodeType){
                //generate a random color
                let shape = nodeType.shape ?? 'diamond';
                let value = nodeType[displayed].eng;
                let color = undefined;
                if(listHasChanged){
                    color = nodeType.color ?? '#'+(Math.random()*0xFFFFFF<<0).toString(16);
                }
                else{
                    color = nodeType.color ?? list_node_type_colors[value];
                }
                list_node_type_colors[value] = color;
                let style = {
                    selector: 'node[type = "'+value+'"]',
                    style: {
                        'background-color': color,
                        'label': 'data(name)',
                        'font-size': 25,
                        'shape': shape
                    }
                };
                nodeStyle.push(style);
            });
            return nodeStyle;
        }
        function updateStyle(){
            // remove the old style
            cy.style().resetToDefault();
            // add the new style
            let nodeStyle = generateNodeStyle();

            // concat style and nodeStyle to get the new style
            let newStyle = style.concat(nodeStyle);

            cy.style(newStyle);
        }

        // According to the list, we change the 'type' of each node.
        // It uses the window.persons variable
        function updateData(){
            // foreach nodes
            cy.nodes().forEach(function(node){
               //update the data
                let type = window.persons[node.id()][listFieldId];
                node.data("type", type);
            });

        }
        function updateLegend(){
            let displayed = getDisplayedValue(listNodeTypeId);
            let legend = "";
            listNodeType.forEach(function(nodeType){
                let value = nodeType[displayed].eng;
                let color = list_node_type_colors[value];

                //check if the value is used in at least one node (check it through window.persons)
                let used = false;
                for(let person in window.persons){
                    if(window.persons[person][listFieldId] == value){
                        used = true;
                        break;
                    }
                }
                if(used) {
                    legend += '<em class="fas fa-circle" style="color: ' + color + '">' + value + '</em>';
                }
            });
            $("#legendList").html(legend);
        }

        function clear(){
            // clear the graph
            cy.remove(cy.elements());
            // redraw the graph
            cy.add(data);
            // clear the from and to lists
            $("#from").val("");
            $("#select2-from-container").text("-- Select the first person --");

            $("#to").val("");
            $("#select2-to-container").text("-- Select the second person --");

            updateStyle();
            // Change the button text to show the betweenness centrality
            $("#betweenness_centrality").text("Show centrality");
            cy.layout(dagre_layout).run();

            $("#select2-layout-container").text("dagre");
        }
    });
}
drawGraph();

