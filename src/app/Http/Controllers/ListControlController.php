<?php

namespace App\Http\Controllers;

use App\Models\ListControl;
use App\Http\Requests\StoreListControlRequest;
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
        $lists = ListControl::where("deleted", 0)
            ->get();
        return view("lists_control.index", compact("lists"));
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
     * @param  \App\Http\Requests\StoreListControlRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreListControlRequest $request)
    {
        // TODO : créer la migration
        // TODO : créer le modèle
        ListControl::create($request->validated());
        return redirect()->route("lists_control.index");
    }

    /**
     * Display the specified resource.
     *
     * @param  String  $id
     * @return \Illuminate\Http\Response
     */
    public function show(String $id)
    {
        $list=ListControl::find($id);
        return view("lists_control.show", compact("list"));
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
