<?php

namespace App\Http\Controllers;

use App\Models\Field;
use Illuminate\Http\Request;
use App\Http\Requests\StoreFildsRequest;
use App\Http\Requests\UpdateFildsRequest;

class FieldsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $fields = Field::orderBy("order")
            ->orderBy("required")
            ->get();
        return view("fields.index", compact('fields'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        return view("fields.create");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\StoreFieldRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreFieldRequest $request)
    {
        Field::create($request->validated());
        return redirect(route("fields.index"));
    }

    /**
     * Display the specified resource.
     *
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $field = Field::find($id);
        return route("fields.show", compact('field'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $field = Field::find($id);
        return route("fields.edit", compact('field'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateFieldsRequest $request, $id)
    {
        $field = Field::find($id);
        $field->update($request->validate());

        return redirect(route("fields.index"));
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
