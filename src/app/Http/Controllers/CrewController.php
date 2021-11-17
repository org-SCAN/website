<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StoreCrewRequest;
use App\Http\Requests\UpdateCrewRequest;
use App\Models\Crew;

class CrewController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
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
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
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
        //
    }
}
