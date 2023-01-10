<?php

namespace App\Http\Controllers;


use App\Http\Requests\addUserToCrewRequest;
use App\Http\Requests\StoreCrewRequest;
use App\Http\Requests\UpdateCrewRequest;
use App\Models\ApiLog;
use App\Models\Crew;
use App\Models\Field;
use App\Models\ListControl;
use App\Models\ListDataType;
use App\Models\Translation;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;


class CrewController extends Controller
{

    /**
     * Create the controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->authorizeResource(Crew::class, 'crew');
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $crews = Crew::orderBy('name')->get();
        return view("crew.index", compact("crews"));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\StoreCrewRequest  $request
     * @return Response
     */
    public function store(StoreCrewRequest $request)
    {
        $crew = $request->validated();
        $crew = Crew::create($crew);
        $user = Auth::user();
        $user->crew_id = $crew->id;
        $user->save();

   // create the GDPR field

        $field = Field::create([
            "title" => "I give my consent for you to collect and use my personal data as described in SCAN policy.",
            "label" => "GDPR",
            "data_type_id" => ListDataType::firstWhere('name', 'Yes / No')->id,
            "required" => 1,
            "importance" => 0,
            "status" => 2,
            "validation_laravel" => ListDataType::firstWhere('name', 'Yes / No')->validation.'|accepted|required',
            "crew_id" => $crew->id,
            "order" => 1,
            "api_log" =>  ApiLog::createFromRequest($request,'Field')->id
        ]);


        $listField = ListControl::firstWhere('name',
            'Field');
        //translate the field
        Translation::handleTranslation($listField,
            $field->{$listField->key_value},
            $field->{$listField->displayed_value});


        return redirect()->route("crew.index");
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view("crew.create");
    }

    /**
     * Display the specified resource.
     *
     * @param  Crew  $crew
     * @return Response
     */
    public function show(Crew $crew)
    {
        $users = $crew->users;
        return view("crew.show", compact("crew"));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Crew  $crew
     * @return Response
     */
    public function edit(Crew $crew)
    {
        return view("crew.edit", compact("crew"));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateCrewRequest $request
     * @param Crew $crew
     * @return Response
     */
    public function update(UpdateCrewRequest $request, Crew $crew)
    {
        $upd = $request->validated();
        $crew->update($upd);

        return redirect()->route("crew.index");
    }

    public function addUser(addUserToCrewRequest $request, Crew $crew)
    {
        $user = User::find($request->validated()['user']);
        $user->crew_id = $crew->id;
        $user->save();
        return view("crew.show", compact("crew"));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Crew $crew
     * @return Response
     */
    public function destroy(Crew $crew)
    {
        foreach ($crew->users as $user) {
            $user->crew_id = Crew::getDefaultCrewId();
            $user->save();
        }
        $crew->delete();
        return redirect()->route("crew.index");
    }
}
