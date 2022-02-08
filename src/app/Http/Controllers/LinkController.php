<?php

namespace App\Http\Controllers;

use App\Http\Requests\FileLinkRequest;
use App\Http\Requests\StoreLinkApiRequest;
use App\Http\Requests\StoreLinkRequest;
use App\Http\Requests\UpdateLinkRequest;
use App\Models\ApiLog;
use App\Models\Link;
use App\Models\ListControl;
use App\Models\Refugee;
use App\Models\Relation;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class LinkController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $links = Link::all();
        return view("links.index", compact('links'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $lists["refugees"] = Refugee::getAllBestDescriptiveValues();
        $lists["relations"] = array_column(Relation::all()->toArray(), ListControl::where('name', "Relation")->first()->displayed_value, "id");
        return view("links.create", compact("lists"));
    }


    /**
     * Show the form for creating a new resource from a json file.
     *
     * @return Response
     */
    public function createFromJson()
    {
        //abort_if(Gate::denies('manage_refugees_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
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
     * @param string $id
     * @return Response
     */
    public function show($id)
    {
        $link = Link::find($id);
        //return view("links.show", compact("links"));
        return redirect()->route("links.edit", compact("link"));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        $link = Link::find($id);
        $lists["relations"] = [$link->getRelationId() => $link->relation] + array_column(Relation::all()->toArray(), ListControl::where('name', "Relation")->first()->displayed_value, "id");
        return view("links.edit", compact("link","lists"));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(UpdateLinkRequest $request, $id)
    {

        $log = ApiLog::createFromRequest($request, "Link");
        $link = $request->validated();
        $link["api_log"] = $log->id;
        $link = Link::find($id)->update($link);

        return redirect()->route("links.index");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param string $id
     * @return Response
     */
    public function destroy($id)
    {
        Link::find($id)->delete();
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
            foreach ($request->validated() as $link) {

                $relation["api_log"] = $log->id;
                $relation["application_id"] = $log->application_id;

                $from = Refugee::getRefugeeIdFromReference($link["from_unique_id"], $relation["application_id"]);
                if ($from != null) {
                    $relation["from"] = $from;
                } else {
                    $log->update(["response" => "Error : " . $link["from_unique_id"] . " not found with application id : " . $relation["application_id"]]);
                    return response("Error : " . $link["to_unique_id"] . " not found with application id : " . $relation["application_id"], 500);
                }

                $to = Refugee::getRefugeeIdFromReference($link["to_unique_id"], $relation["application_id"]);

                if ($to != null) {
                    $relation["to"] = $to;
                } else {
                    $log->update(["response" => "Error : " . $link["to_unique_id"] . " not found with application id : " . $relation["application_id"]]);
                    return response("Error : " . $link["to_unique_id"] . " not found with application id : " . $relation["application_id"], 500);
                }

                $relation["relation"] = $link["relation"];
                if (isset($link["detail"])) {
                    $relation["detail"] = $link["detail"];
                }
                //from there handle API request relation
                $stored_link = Link::handleApiRequest($relation);
                if ($stored_link == null) {
                    $log->update(["response" => "Error while creating a relation"]);
                    return response("Error while creating this refugee :" . json_encode($link), 500);
                }
            }
            return response("Success !", 201);
        }
        $log->update(["response"=>"Bad token access"]);
        return response("Your token can't be use to send datas", 403);
    }
}
