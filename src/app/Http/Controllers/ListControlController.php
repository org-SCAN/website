<?php

namespace App\Http\Controllers;

use App\Models\ListControl;
use App\Http\Requests\StoreListControlRequest;
use App\Http\Requests\UpdateListControlRequest;
use App\Traits\Uuids;
use Illuminate\Support\Facades\URL;

class ListControlController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $lists = ListControl::all();
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
     * This function is used to add an element to an existing list
     *
     * @param ListControl $lists_control
     * @return \Illuminate\Http\Response
     *
     */
    public function addToList(ListControl $lists_control)
    {

        return view("lists_control.add_to_list", compact("lists_control"));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\StoreListControlRequest $request
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
     * @param ListControl $lists_control
     * @return \Illuminate\Http\Response
     */
    public function show(ListControl $lists_control)
    {
        $list_content = $lists_control->getListContent()->toArray();
        return view("lists_control.show", compact("lists_control", "list_content"));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param ListControl $lists_control
     * @return \Illuminate\Http\Response
     */
    public function edit(ListControl $lists_control)
    {
        //$fields = $lists_control
        return view("lists_control.edit", compact("lists_control"));
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
     * @param ListControl $lists_control
     * @return \Illuminate\Http\Response
     */
    public function destroy(ListControl $lists_control)
    {
        $lists_control->delete();
        return redirect()->route("lists_control.index");
    }
}
