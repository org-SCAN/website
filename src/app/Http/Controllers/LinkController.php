<?php

namespace App\Http\Controllers;

use App\Http\Requests\FileLinkRequest;
use App\Http\Requests\StoreLinkApiRequest;
use App\Http\Requests\StoreLinkRequest;
use App\Http\Requests\UpdateLinkRequest;
use App\Models\ApiLog;
use App\Models\Crew;
use App\Models\Link;
use App\Models\ListControl;
use App\Models\ListRelation;
use App\Models\ListRelationType;
use App\Models\Refugee;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class LinkController extends Controller
{
    /**
     * Create the controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->authorizeResource(Link::class,
            'link');
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index() {
        $links = Link::whereRelation('RefugeeFrom.crew',
            'crews.id',
            Auth::user()->crew->id)->whereRelation('RefugeeTo.crew',
            'crews.id',
            Auth::user()->crew->id)->get();
        return view("links.index",
            compact('links'));
    }

    /**
     * Show the form for creating a new resource from a json file.
     *
     * @return Response
     */
    public function createFromJson() {
        //abort_if(Gate::denies('person_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        return view("links.create_from_json");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(StoreLinkRequest $request) {
        $link = $request->validated();
        $link["date"] = ((isset($link["date"]) && !empty($link["date"])) ? $link["date"] : date('Y-m-d H:i',
            time()));
        $link["api_log"] = ApiLog::createFromRequest($request,
            "Link")->id;

        $relation_type = ListRelationType::list();

        if (isset($link["everyoneFrom"]) || isset($link["everyoneTo"])) {

            if (isset($link["everyoneTo"])) {
                $origin = "from";
                $direction = "to";
            } else {
                $origin = 'to';
                $direction = 'from';
            }
            $person = Refugee::find($link[$origin]);
            if ($person->hasEvent()) {
                $associatedPersonsThroughEvent = $person->event->persons->where("id",'!=',$person->id);
            }
            unset($link["everyoneFrom"]);
            unset($link["everyoneTo"]);
        }

        if (isset($associatedPersonsThroughEvent) && !empty($associatedPersonsThroughEvent) && isset($direction)) {
            foreach ($associatedPersonsThroughEvent as $associatedPerson) {
                $link[$origin] = $person->id;
                $link[$direction] = $associatedPerson->id;
                $new_link = Link::create($link);
                if((isset($link["type"]) && $relation_type[$link["type"]] == "Bilateral")){
                    $link[$origin] = $associatedPerson->id;
                    $link[$direction] = $person->id;
                    Link::create($link);
                }
            }
            return redirect()->route("links.index");
        }

        $new_link = Link::create($link);
        if((isset($link["type"]) && $relation_type[$link["type"]] == "Bilateral")){
            $old_from = $link["from"];
            $link['from'] = $link['to'];
            $link['to'] = $old_from;
            Link::create($link);
        }
        return redirect()->route("links.index");
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create($origin = null,
        Refugee $refugee = null) {
        $lists["refugees"] = Refugee::getAllBestDescriptiveValues();
        $lists["relations"] = array_column(ListRelation::all()->toArray(),
            ListControl::where('name',
                "ListRelation")->first()->displayed_value,
            "id");
        return view("links.create",
            compact("lists",
                'origin',
                'refugee'));
    }

    /**
     * Store a newly created resource from json file in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function storeFromJson(FileLinkRequest $request) {
        $log = ApiLog::createFromRequest($request,
            "Link");
        foreach ($request->validated() as $link) {
            $relation["api_log"] = $log->id;
            $relation["application_id"] = $log->application_id;
            $from = Refugee::getRefugeeIdFromReference($link["from_unique_id"],
                $relation["application_id"]);
            if ($from != null) {
                $relation["from"] = $from;
            } else {
                $log->update(["response" => "Error : ".$link["from_unique_id"]." not found with application id : ".$link["application_id"]]);
                break;
            }

            $to = Refugee::getRefugeeIdFromReference($link["to_unique_id"],
                $relation["application_id"]);

            if ($to != null) {
                $relation["to"] = $to;
            } else {
                $log->update(["response" => "Error : ".$link["to_unique_id"]." not found with application id : ".$link["application_id"]]);
                break;
            }

            $relation["relation"] = $link["relation"];
            if (isset($link["detail"]) && !empty($link["detail"])) {
                $relation["detail"] = $link["detail"];
            }

            //from there handle API request relation
            $stored_link = Link::handleApiRequest($relation);
            if ($stored_link == null) {
                $log->update(["response" => "Error while creating a relation"]);
                return response("Error while creating this refugee :".json_encode($link),
                    500);
            }
        }
        return redirect()->route("links.index");
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  Link  $link
     * @return Response
     */
    public function update(UpdateLinkRequest $request,
        Link $link) {

        $log = ApiLog::createFromRequest($request,
            "Link");
        $linkv = $request->validated();
        $linkv["api_log"] = $log->id;
        $link->update($linkv);

        return redirect()->route("links.index");
    }

    /**
     * Handle the API request
     *
     * @param  StoreLinkApiRequest  $request
     * @return array
     */
    public static function handleApiRequest(StoreLinkApiRequest $request) {
        $log = ApiLog::createFromRequest($request,
            "Link");

        if ($request->user()->tokenCan("update")) {
            $responseArray = [];
            foreach ($request->validated() as $link) {
                $link['api_log'] = $log->id;
                $link["application_id"] = $log->application_id;
                //check
                if (key_exists("id", $link)) { // update
                    $linkUpdate = Link::find($link["id"]);
                    $linkUpdate->update($link);
                    $link = $linkUpdate;
                } else {
                    $potentialLink = Link::where('from', $link["from"])
                        ->where('to', $link["to"])
                        ->where('relation', $link["relation"])
                        ->first();
                    if ($potentialLink != null) {
                        $link = $potentialLink;
                    } else {
                        $link = Link::create($link);
                    }
                }
                array_push($responseArray,
                    $link->id);
            }
            return response(json_encode($responseArray),
                201,
                ['Content-Type' => "application/json"]);
        }
        $log->update(["response" => "Bad token access"]);
        return response("Your token can't be use to send datas",
            403);
    }

    /**
     * Display the specified resource.
     *
     * @param  Link  $link
     * @return Response
     */
    public function show(Link $link) {
        //return view("links.show", compact("links"));
        return redirect()->route("links.edit",
            compact("link"));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Link  $link
     * @return Response
     */
    public function edit(Link $link) {
        $lists["relations"] = [$link->relation->id => $link->relation->displayed_value_content] + array_column(ListRelation::all()->toArray(),
                ListControl::where('name',
                    "ListRelation")->first()->displayed_value,
                "id");

        return view("links.edit",
            compact("link",
                "lists"));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Link  $link
     * @return Response
     */
    public function destroy(Link $link) {
        $link->delete();
        return redirect()->route("links.index");
    }

    /**
     * Get relations API. It returns all the relations in a specific crew
     * @param  Request  $request
     * @param  Crew  $crew
     * @return Response
     */
    public function apiGetRelations(Request $request,
        Crew $crew = null) {
        if ($request->user()->tokenCan("read")) {
            if ($crew == null) {
                $crew = $request->user()->crew;
            }
            return response(json_encode($crew->relations->makeHidden([
                "created_at",
                'updated_at',
                'api_log',
                'application_id',
                'laravel_through_key',
                "deleted_at",
            ])->toArray()),
                200,
                ['Content-Type' => "application/json"]);
        } else {
            return response("Your token can't be use to get datas",
                403);
        }

    }
}
