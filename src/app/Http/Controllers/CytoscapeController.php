<?php
namespace App\Http\Controllers;

use App\Models\Cytoscape;
use App\Models\Field;
use App\Models\Link;
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
        $relations = Link::whereRelation('RefugeeFrom.crew', 'crews.id', Auth::user()->crew->id)
            ->whereRelation('RefugeeTo.crew', 'crews.id', Auth::user()->crew->id)
            ->get();

        //get the role field
        $role_list = ListControl::firstWhere('name', 'ListRole');
        $role_field = Field::whereCrewId(Auth::user()->crew->id)->where('linked_list', $role_list->id);

        $links = array();
        $nodes = array();
        $refugees = array();
        foreach ($relations as $relation) {

            $node["data"] = array();
            $node["data"]["id"] = $relation->getFromId();
            $node["data"]["name"] = $relation->refugeeFrom->best_descriptive_value;
            if ($role_field->exists() && $relation->refugeeFrom->fields->where('id', $role_field->first()->id)->count() > 0) {
                $model = "App\Models\\" . $role_list->name; // App\Models\ListRole
                $value = $model::find($relation->refugeeFrom->fields->firstWhere('id', $role_field->first()->id)->pivot->value)->{$role_list->displayed_value};
                $node["data"]["role"] = $value;
            }
            array_push($nodes, $node);

            $node["data"] = array();
            $node["data"]["id"] = $relation->getToId();
            $node["data"]["name"] = $relation->refugeeTo->best_descriptive_value;
            if ($role_field->exists() && $relation->refugeeTo->fields->where('id', $role_field->first()->id)->count() > 0) {
                $model = "App\Models\\" . $role_list->name; // App\Models\ListRole
                $value = $model::find($relation->refugeeTo->fields->firstWhere('id', $role_field->first()->id)->pivot->value)->{$role_list->displayed_value};
                $node["data"]["role"] = $value;
            }
            array_push($nodes, $node);

            $refugees[$relation->getToId()] = $relation->refugeeTo->best_descriptive_value;
            $refugees[$relation->getFromId()] = $relation->refugeeFrom->best_descriptive_value;

            $link["data"] = array();
            $link["data"]["id"] = $relation->id;
            $link["data"]["label"] = $relation->relation->displayed_value_content;
            $link["data"]["weight"] = $relation->relation->weight;
            $link["data"]["source"] = $relation->getFromId();
            $link["data"]["target"] = $relation->getToId();
            $link["data"]["detail"] = $relation->detail;
            array_push($links, $link);
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

        // file_put_contents("js/cytoscape/content.json",json_encode(array_merge($nodes, $links)));
        Storage::disk('public')->put('content.json', json_encode(array_merge($nodes, $links)));
        return view("cytoscape.index", compact("relations", "refugees"));
    }
}
