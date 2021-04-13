<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Refugee;

class ManageRefugeesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $refugees = Refugee::all();

        return view("manage_refugees.index", compact("refugees"));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view("manage_refugees.create");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Refugee::create($request->validate());
        return redirect()->route("manage_refugees.index");
    }

    /**
     * Display the specified resource.
     *
     * @param  Refugee  $refugee
     * @return \Illuminate\Http\Response
     */
    public function show(Refugee $refugee)
    {
        return view("manage_refugees.show", compact($refugee));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Refugee  $refugee
     * @return \Illuminate\Http\Response
     */
    public function edit(Refugee $refugee)
    {
        return view("manage_refugees.edit", compact($refugee));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\RequestRefugeeRequest $request
     * @param  Refugee $refugee
     * @return \Illuminate\Http\Response
     */
    public function update(RequestRefugeeRequest $request, Refugee $refugee)
    {
        $refugee->update($request->validate());
        return redirect()->route("manage_refugees.index");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Refugee $refugee)
    {
        $refugee->delete();
        return redirect()->route("manage_refugees.index");
    }
}
