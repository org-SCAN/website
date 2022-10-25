<?php

namespace App\Http\Controllers;


use App\Http\Requests\addUserToCrewRequest;
use App\Http\Requests\StoreCrewRequest;
use App\Http\Requests\UpdateCrewRequest;
use App\Models\Crew;
use App\Models\User;


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
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $crews = Crew::orderBy('name')->get();
        return view("crew.index", compact("crews"));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view("crew.create");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\StoreCrewRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCrewRequest $request)
    {
        $crew = $request->validated();
        Crew::create($crew);
        return redirect()->route("crew.index");
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Crew $crew
     * @return \Illuminate\Http\Response
     */
    public function show(Crew $crew)
    {
        $users = $crew->users;
        return view("crew.show", compact("crew"));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Crew $crew
     * @return \Illuminate\Http\Response
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
     * @return \Illuminate\Http\Response
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
     * @return \Illuminate\Http\Response
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
