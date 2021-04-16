<?php

namespace App\Http\Controllers;

use App\Models\ListControl;
use App\Http\Requests\StoreListControlRequest;
use App\Http\Requests\UpdateListControlRequest;
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
     * @param  String  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(String $id)
    {
        $list=ListControl::find($id);
        return view("lists_control.edit", compact("list"));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateListControlRequest  $request
     * @param  \App\Models\ListControl  $listControl
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateListControlRequest $request, ListControl $listControl)
    {
        $listControl->update($request->validated());
        return redirect()->route("lists_control.index");
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
