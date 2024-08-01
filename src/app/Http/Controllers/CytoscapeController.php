<?php
namespace App\Http\Controllers;

use App\Models\Cytoscape;
use App\Models\Field;
use App\Models\Link;
use App\Models\Refugee;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CytoscapeController extends Controller
{
    /**
     * Create the controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->authorizeResource(Cytoscape::class, 'cytoscape');
    }

    public function index()
    {
        // get the lists associated to the given crew
        $lists = Field::whereCrewId(Auth::user()->crew_id)->whereRelation('dataType','name', 'List')->get();
        $lists_name = $lists->pluck('title', 'id');
        $field_list = json_encode($lists->pluck('linked_list','id'));
        $refugee_to_event_relations = Link::whereRelation('RefugeeFrom.crew', 'crews.id', Auth::user()->crew->id)
            ->whereRelation('EventTo.crew', 'crews.id', Auth::user()->crew->id)
            ->get();
        $refugee_to_place_relations = Link::whereRelation('RefugeeFrom.crew', 'crews.id', Auth::user()->crew->id)
            ->whereRelation('PlaceTo.crew', 'crews.id', Auth::user()->crew->id)
            ->get();
        $refugee_to_refugee_relations = Link::whereRelation('RefugeeFrom.crew', 'crews.id', Auth::user()->crew->id)
            ->whereRelation('RefugeeTo.crew', 'crews.id', Auth::user()->crew->id)
            ->get();
        $event_to_event_relations = Link::whereRelation('EventFrom.crew', 'crews.id', Auth::user()->crew->id)
            ->whereRelation('EventTo.crew', 'crews.id', Auth::user()->crew->id)
            ->get();
        $event_to_place_relations = Link::whereRelation('EventFrom.crew', 'crews.id', Auth::user()->crew->id)
            ->whereRelation('PlaceTo.crew', 'crews.id', Auth::user()->crew->id)
            ->get();
        $event_to_refugee_relations = Link::whereRelation('EventFrom.crew', 'crews.id', Auth::user()->crew->id)
            ->whereRelation('RefugeeTo.crew', 'crews.id', Auth::user()->crew->id)
            ->get();
        $place_to_place_relations = Link::whereRelation('PlaceFrom.crew', 'crews.id', Auth::user()->crew->id)
            ->whereRelation('PlaceTo.crew', 'crews.id', Auth::user()->crew->id)
            ->get();
        $place_to_refugee_relations = Link::whereRelation('PlaceFrom.crew', 'crews.id', Auth::user()->crew->id)
            ->whereRelation('RefugeeTo.crew', 'crews.id', Auth::user()->crew->id)
            ->get();
        $place_to_event_relations = Link::whereRelation('PlaceFrom.crew', 'crews.id', Auth::user()->crew->id)
            ->whereRelation('EventTo.crew', 'crews.id', Auth::user()->crew->id)
            ->get();
        $relations = $refugee_to_event_relations
            ->merge($refugee_to_place_relations)
            ->merge($refugee_to_refugee_relations)
            ->merge($event_to_event_relations)
            ->merge($event_to_place_relations)
            ->merge($event_to_refugee_relations)
            ->merge($place_to_place_relations)
            ->merge($place_to_refugee_relations)
            ->merge($place_to_event_relations);

        //get the role field

        $links = array();
        $nodes = array();
        $refugees = array();
        $events = array();
        $places = array();
        $used_relations = array();
        foreach ($relations as $relation) {

            $node["data"] = array();
            $node["data"]["id"] = $relation->getFromId();
            $node["data"]["name"] = $relation->refugeeFrom?->best_descriptive_value
                ?? $relation->eventFrom?->name
                ?? $relation->placeFrom?->name
                ?? "Ø";

            array_push($nodes, $node);

            $node["data"] = array();
            $node["data"]["id"] = $relation->getToId();
            $node["data"]["name"] = $relation->refugeeTo?->best_descriptive_value
                ?? $relation->eventTo?->name
                ?? $relation->placeTo?->name
                ?? "Ø";

            array_push($nodes, $node);

            $refugees[$relation->getToId()] = $relation->refugeeTo->best_descriptive_value ?? "Ø";
            $refugees[$relation->getFromId()] = $relation->refugeeFrom->best_descriptive_value ?? "Ø";
            $events[$relation->getToId()] = $relation->eventTo->name ?? "Ø";
            $events[$relation->getFromId()] = $relation->eventFrom->name ?? "Ø";
            $places[$relation->getToId()] = $relation->placeTo->name ?? "Ø";
            $places[$relation->getFromId()] = $relation->placeFrom->name ?? "Ø";

            $link["data"] = array();
            $link["data"]["id"] = $relation->id;
            $link["data"]["label"] = $relation->relation->displayed_value_content;
            $link["data"]["weight"] = $relation->relation->weight;
            $link["data"]["source"] = $relation->getFromId();
            $link["data"]["target"] = $relation->getToId();
            $link["data"]["detail"] = $relation->detail;
            $link["data"]["date"] = date("Y-m-d", strtotime($relation->date));
            //Concatenate detail and date to display in the tooltip
            $link["data"]["infos"] = ($relation->detail ? $relation->detail : "Ø") . "/" . date("Y-m-d", strtotime($relation->date));
            array_push($links, $link);
            $used_relations[$relation->relation->id] = $relation->relation;

        }

        /**
         * If a link exists in the 2 directions between 2 nodes, we only keep the one and delete the other.
         * We add a tag ['type'] = 'bilateral' to the link.
         */

        $toUnset = [];
        foreach ($links as $key => $link) {
            foreach ($links as $key2 => $link2) {
                if (!in_array($key2, $toUnset) && !in_array($key, $toUnset) &&
                    $link['data']['id'] != $link2["data"]["id"] && // not the same relation
                    $link['data']['label'] == $link2["data"]["label"] && // same relation type
                    $link['data']['source'] == $link2['data']['target'] &&  // same source and target
                    $link['data']['target'] == $link2['data']['source']) { // but in the opposite direction

                    $links[$key]['data']['type'] = 'bilateral';
                    $toUnset[] = $key2;

                }
            }
        }
        foreach ($toUnset as $key) {
            unset($links[$key]);
        }

        $cytoscape_data = json_encode(array_merge($nodes, $links));
        // get the persons id from the nodes
        $persons = array();
        foreach ($nodes as $node) {
            array_push($persons, $node["data"]["id"]);
        }
        $persons = json_encode(Refugee::formatRefugeesData(Refugee::whereIn('id', $persons)->get()));


        // file_put_contents("js/cytoscape/content.json",json_encode(array_merge($nodes, $links)));
        Storage::disk('public')->put('content.json', $cytoscape_data);
        return view("cytoscape.index", compact("relations", "refugees", "events", "places","lists_name", "field_list", "persons", "used_relations"));
    }
}
