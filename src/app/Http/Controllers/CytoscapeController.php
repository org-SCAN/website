<?php
namespace App\Http\Controllers;

use App\Models\Cytoscape;
use App\Models\Field;
use App\Models\ListControl;
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
        $relations = \App\Models\Link::whereRelation('RefugeeFrom.crew', 'crews.id', Auth::user()->crew->id)
            ->whereRelation('RefugeeTo.crew', 'crews.id', Auth::user()->crew->id)
            ->get();

        //get the role field
        $role_list = ListControl::firstWhere('name', 'ListRole');
        $role_field = Field::whereCrewId(Auth::user()->crew->id)->firstWhere('linked_list', $role_list->id);
        /*
            $nodes = array();
            foreach ($refugees as $refugee){
                $node["data"] = array();
                $node["data"]["id"] = $refugee->id;
                $node["data"]["name"] = $refugee->full_name;
                array_push($nodes, $node);
            }*/

        $links = array();
        $nodes = array();
        $refugees = array();
        foreach ($relations as $relation) {

            $node["data"] = array();
            $node["data"]["id"] = $relation->getFromId();
            $node["data"]["name"] = $relation->refugeeFrom->best_descriptive_value;
            if ($role_field->exists()) {
                $model = "App\Models\\" . $role_list->name; // App\Models\ListRole
                $value = $model::find($relation->refugeeFrom->fields->firstWhere('id', $role_field->id)->pivot->value)->{$role_list->displayed_value};
                $node["data"]["role"] = $value;
            }
            array_push($nodes, $node);

            $node["data"] = array();
            $node["data"]["id"] = $relation->getToId();
            $node["data"]["name"] = $relation->refugeeTo->best_descriptive_value;
            if ($role_field->exists()) {
                $model = "App\Models\\" . $role_list->name; // App\Models\ListRole
                $value = $model::find($relation->refugeeTo->fields->firstWhere('id', $role_field->id)->pivot->value)->{$role_list->displayed_value};
                $node["data"]["role"] = $value;
            }
            array_push($nodes, $node);

            $refugees[$relation->getToId()] = $relation->refugeeTo->best_descriptive_value;
            $refugees[$relation->getFromId()] = $relation->refugeeFrom->best_descriptive_value;

            $link["data"] = array();
            $link["data"]["id"] = $relation->id;
            $link["data"]["label"] = $relation->relation;
            $link["data"]["weight"] = $relation->getRelationWeight();
            $link["data"]["source"] = $relation->getFromId();
            $link["data"]["target"] = $relation->getToId();
            $link["data"]["detail"] = $relation->detail;
            array_push($links, $link);
        }

        // file_put_contents("js/cytoscape/content.json",json_encode(array_merge($nodes, $links)));
        Storage::disk('public')->put('content.json', json_encode(array_merge($nodes, $links)));
        return view("cytoscape.index", compact("relations", "refugees"));
    }
}
