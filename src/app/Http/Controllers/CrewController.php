<?php

namespace App\Http\Controllers;


use App\Http\Requests\StoreCrewRequest;
use App\Http\Requests\UpdateCrewRequest;
use App\Models\Crew;


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

    /**
     * Remove the specified resource from storage.
     *
     * @param Crew $crew
     * @return \Illuminate\Http\Response
     */
    public function destroy(Crew $crew)
    {
        //TODO : moove attached user to the default team
        // $this->authorize("delete", Auth::user());
        $crew->delete();
        return redirect()->route("crew.index");
    }
}
