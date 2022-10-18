<?php

namespace App\Http\Controllers;

use App\Http\Requests\FileLinkRequest;
use App\Http\Requests\StoreLinkApiRequest;
use App\Http\Requests\StoreLinkRequest;
use App\Http\Requests\UpdateLinkRequest;
use App\Models\ApiLog;
use App\Models\Link;
use App\Models\ListControl;
use App\Models\ListRelation;
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
    public function __construct()
    {
        $this->authorizeResource(Link::class, 'link');
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $links = Link::whereRelation('RefugeeFrom.crew', 'crews.id', Auth::user()->crew->id)
            ->whereRelation('RefugeeTo.crew', 'crews.id', Auth::user()->crew->id)
            ->get();
        return view("links.index", compact('links'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create($origin = null, Refugee $refugee = null)
    {
        $lists["refugees"] = Refugee::getAllBestDescriptiveValues();
        $lists["relations"] = array_column(ListRelation::all()->toArray(), ListControl::where('name', "ListRelation")->first()->displayed_value, "id");
        return view("links.create", compact("lists", 'origin', 'refugee'));
    }


    /**
     * Show the form for creating a new resource from a json file.
     *
     * @return Response
     */
    public function createFromJson()
    {
        //abort_if(Gate::denies('person_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        return view("links.create_from_json");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(StoreLinkRequest $request)
    {
        $link = $request->validated();
        $link["date"] = ((isset($link["date"]) && !empty($link["date"])) ? $link["date"] : date('Y-m-d H:i', time()));
        $log = ApiLog::createFromRequest($request, "Link");
        $link["api_log"] = $log->id;
        Link::create($link);
        return redirect()->route("links.index");
    }

    /**
     * Store a newly created resource from json file in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function storeFromJson(FileLinkRequest $request)
    {
        $log = ApiLog::createFromRequest($request, "Link");
        foreach ($request->validated() as $link) {
            $relation["api_log"] = $log->id;
            $relation["application_id"] = $log->application_id;
            $from = Refugee::getRefugeeIdFromReference($link["from_unique_id"], $relation["application_id"]);
            if ($from != null) {
                $relation["from"] = $from;
            } else {
                $log->update(["response" => "Error : " . $link["from_unique_id"] . " not found with application id : " . $link["application_id"]]);
                break;
            }

            $to = Refugee::getRefugeeIdFromReference($link["to_unique_id"], $relation["application_id"]);

            if ($to != null) {
                $relation["to"] = $to;
            } else {
                $log->update(["response" => "Error : " . $link["to_unique_id"] . " not found with application id : " . $link["application_id"]]);
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
                return response("Error while creating this refugee :" . json_encode($link), 500);
            }
        }
        return redirect()->route("links.index");
    }

    /**
     * Display the specified resource.
     *
     * @param Link $link
     * @return Response
     */
    public function show(Link $link)
    {
        //return view("links.show", compact("links"));
        return redirect()->route("links.edit", compact("link"));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Link $link
     * @return Response
     */
    public function edit(Link $link)
    {
        $lists["relations"] = [$link->getRelationId() => $link->relation] + array_column(ListRelation::all()->toArray(), ListControl::where('name', "ListRelation")->first()->displayed_value, "id");
        return view("links.edit", compact("link", "lists"));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Link $link
     * @return Response
     */
    public function update(UpdateLinkRequest $request, Link $link)
    {

        $log = ApiLog::createFromRequest($request, "Link");
        $linkv = $request->validated();
        $linkv["api_log"] = $log->id;
        $link->update($linkv);

        return redirect()->route("links.index");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Link $link
     * @return Response
     */
    public function destroy(Link $link)
    {
        $link->delete();
        return redirect()->route("links.index");
    }

    /**
     * Handle the API request
     *
     * @param  StoreLinkApiRequest  $request
     * @return array
     */
    public static function handleApiRequest(StoreLinkApiRequest $request)
    {
        $log = ApiLog::createFromRequest($request, "Link");

        if($request->user()->tokenCan("update")){
            $responseArray = array();
            foreach ($request->validated() as $link) {
                $link['api_log'] = $log->id;
                $link["application_id"] = $log->application_id;
                //check
                if (key_exists("id", $link)) {
                    $linkUpdate = Link::find($link["id"]);
                    $linkUpdate->update($link);
                    $link = $linkUpdate;
                } else {
                    $potentialLink = Link::where('from', $link["from"])->where('to', $link["to"])->where('relation', $link["relation"])->first();
                    if ($potentialLink != null) {
                        $link = $potentialLink;
                    } else {
                        $link = Link::create($link);
                    }
                }
                array_push($responseArray, $link->id);
            }
            return response(json_encode($responseArray), 201, ['Content-Type' => "application/json"]);
        }
        $log->update(["response"=>"Bad token access"]);
        return response("Your token can't be use to send datas", 403);
    }
}
