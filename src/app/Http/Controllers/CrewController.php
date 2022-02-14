<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StoreCrewRequest;
use App\Http\Requests\UpdateCrewRequest;
use App\Models\Crew;
use Illuminate\Support\Facades\Auth;

class CrewController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize("viewAny", Auth::user());
        $crews = Crew::all();
        return view("crew.index", compact("crews"));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize("create", Auth::user());
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
        $this->authorize("create", Auth::user());
        $crew = $request->validated();
        Crew::create($crew);
        return redirect()->route("crew.index");
    }

    /**
     * Display the specified resource.
     *
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $this->authorize("view", Auth::user());
        $crew = Crew::find($id);
        return view("crew.show", compact("crew"));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $this->authorize("update", Auth::user());
        $crew = Crew::find($id);
        return view("crew.edit", compact("crew"));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateCrewRequest $request
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCrewRequest $request, $id)
    {
        $this->authorize("update", Auth::user());
        $crew = $request->validated();
        Crew::find($id)->update($crew);

        return redirect()->route("crew.index");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //TODO : moove attached user to the default team
        $this->authorize("delete", Auth::user());
        Crew::find($id)->delete();
        return redirect()->route("crew.index");
    }
}
