<?php

namespace App\Http\Controllers;

use App\Models\ListControl;
use App\Http\Request\StoreListControlRequestRequest;
use App\Traits\Uuids;

class ListControlController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $lists = ListControl::where("deleted", 0);
        return view("lists_control.index", compact("lits"));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view("lists_control.create");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\StoreListControlRequestRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreListControlRequestRequest $request)
    {
        // TODO : créer la migration
        // TODO : créer le modèle
        ListControl::create($request->validated());
        return redirect()->route("list_control.index");
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ListControl  $listControl
     * @return \Illuminate\Http\Response
     */
    public function show(ListControl $listControl)
    {

        return view("lists_control.show", compact("listsControl"));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ListControl  $listControl
     * @return \Illuminate\Http\Response
     */
    public function edit(ListControl $listControl)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ListControl  $listControl
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ListControl $listControl)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ListControl  $listControl
     * @return \Illuminate\Http\Response
     */
    public function destroy(ListControl $listControl)
    {
        //
    }
}
