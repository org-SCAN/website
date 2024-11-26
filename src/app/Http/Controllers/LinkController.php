<?php

namespace App\Http\Controllers;

use App\Http\Requests\FileLinkRequest;
use App\Http\Requests\StoreLinkApiRequest;
use App\Http\Requests\StoreLinkRequest;
use App\Http\Requests\UpdateLinkRequest;
use App\Models\Crew;
use App\Models\Link;
use App\Models\ListControl;
use App\Models\ListRelation;
use App\Models\ListRelationType;
use App\Models\Refugee;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class LinkController extends Controller
{
    /**
     * Create the controller instance.
     *
     * @return void
     */
    public function __construct(
    )
    {
        $this->authorizeResource(Link::class,
            'link');
    }

    /**
     * Handle the API request
     *
     * @param  StoreLinkApiRequest  $request
     * @return Response
     */
    public static function handleApiRequest(
        StoreLinkApiRequest $request
    ) {

        if ($request->user()->tokenCan("update")) {
            $responseArray = [];
            foreach ($request->validated() as $link) {
                $link["application_id"] = $request->header('Application-id') ?? $request->validated()[0]['application_id'] ?? 'website';
                $link["crew_id"] = $request->user()->crew->id;

                if (key_exists("id",
                    $link)) { // update
                    $linkUpdate = Link::find($link["id"]);
                    $linkUpdate->update($link);
                    $link = $linkUpdate;
                } else {
                    //find or create
                    $link = Link::firstOrCreate($link);

                }
                $responseArray[] = $link->id;
            }
            return response(json_encode($responseArray),
                201,
                ['Content-Type' => "application/json"]);
        }
        Log::channel('api')->info("Bad access token",
            ['user' => $request->user()]);
        return response("Your token can't be use to send datas",
            403);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateLinkRequest  $request
     * @param  Link  $link
     * @return RedirectResponse
     */
    public function update(
        UpdateLinkRequest $request,
        Link $link
    ) {

        $link->update($request->validated());

        return redirect()->route("links.index");
    }

    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index()
    {
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
     * @return View
     */
    public function createFromJson(
    )
    {
        return view("links.create_from_json");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  StoreLinkRequest  $request
     * @return RedirectResponse
     */
    public function store(
        StoreLinkRequest $request
    ) {
        $link = $request->validated();
        $link["date"] = ((!empty($link["date"])) ? $link["date"] : date('Y-m-d H:i',
            time()));
        $link["crew_id"] = Auth::user()->crew->id;

        $relation_type = ListRelationType::all()->pluck('type',
            'id')->toArray();

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
                $associatedPersonsThroughEvent = $person->event->persons();
                // dd($person->event);
            }
            unset($link["everyoneFrom"]);
            unset($link["everyoneTo"]);
        }

        if (!empty($associatedPersonsThroughEvent) && isset($direction)) {
            foreach ($associatedPersonsThroughEvent as $associatedPerson) {
                if ($associatedPerson->id == $person->id) {
                    continue;
                }
                $link[$origin] = $person->id;
                $link[$direction] = $associatedPerson->id;
                $link["crew_id"] = Auth::user()->crew->id;
                $new_link = Link::create($link);
                if ((isset($link["type"]) && $relation_type[$link["type"]] == "bilateral")) {
                    $link[$origin] = $associatedPerson->id;
                    $link[$direction] = $person->id;
                    $link["crew_id"] = Auth::user()->crew->id;
                    Link::create($link);
                }
            }
            return redirect()->route("links.index");
        }

        $new_link = Link::create($link);

        if ((isset($link["type"]) && $relation_type[$link["type"]] == "bilateral")) {
            $old_from = $link["from"];
            $link['from'] = $link['to'];
            $link['to'] = $old_from;
            $link["crew_id"] = Auth::user()->crew->id;
            Link::create($link);
        }
        return redirect()->route("links.index");
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return View
     */
    public function create(
        $origin = null,
        Refugee $refugee = null
    ) {
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
     * @param  FileLinkRequest  $request
     * @return Response
     */
    public function storeFromJson(
        FileLinkRequest $request
    ) {

        foreach ($request->validated() as $link) {
            //from there handle API request relation
            $link = Link::findOrCreate($link,
                [
                    "application_id" => $request->header('Application-id') ?? $request->validated()[0]['application_id'] ?? 'website',
                    "crew_id" => Auth::user()->crew->id,
                ]);
            if (!$link) {
                return response("Error while creating this item :".json_encode($link),
                    500);
            }
        }
        return redirect()->route("links.index");
    }

    /**
     * Display the specified resource.
     *
     * @param  Link  $link
     * @return RedirectResponse
     */
    public function show(
        Link $link
    ) {
        return redirect()->route("links.edit",
            compact("link"));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Link  $link
     * @return View
     */
    public function edit(
        Link $link
    ) {
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
     * @return RedirectResponse
     */
    public function destroy(
        Link $link
    ) {
        $link->delete();
        return redirect()->route("links.index");
    }

    /**
     * Get relations API. It returns all the relations in a specific crew
     * @param  Request  $request
     * @param  Crew|null  $crew
     * @return Response
     */
    public function apiGetRelations(
        Request $request,
        Crew $crew = null
    ) {
        if ($request->user()->tokenCan("read")) {
            if ($crew == null) {
                $crew = $request->user()->crew;
            }
            return response(json_encode($crew->relations->makeHidden([
                "created_at",
                'updated_at',
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
