<?php

namespace App\Http\Controllers;

use App\Models\Field;
use App\Http\Requests\StoreRefugeeRequest;
use App\Http\Requests\UpdateRefugeeRequest;
use App\Traits\Uuids;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Refugee;
use Illuminate\Support\Facades\View;

class ManageRefugeesController extends Controller
{
    public function __construct()
    {
        //its just a dummy data object.
        $fields = Field::all();

        // Sharing is caring
        View::share('fields', $fields);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //abort_if(Gate::denies('manage_refugees_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

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
        //abort_if(Gate::denies('manage_refugees_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $fields = Field::all();
        return view("manage_refugees.create", compact("fields"));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRefugeeRequest $request)
    {
        Refugee::create($request->validated());
        return redirect()->route("manage_refugees.index");
    }

    /**
     * Display the specified resource.
     *
     * @param  Uuid  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Uuids $id)
    {
        //abort_if(Gate::denies('manage_refugees_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $refugee = Refugee::where("id", $id);
        return view("manage_refugees.show", compact("refugee"));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Uuid  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Uuid  $id)
    {
        //abort_if(Gate::denies('manage_refugees_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $refugee = Refugee::where("id", $id);
        return view("manage_refugees.edit", compact("refugee"));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\RequestRefugeeRequest $request
     * @param  Refugee $refugee
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRefugeeRequest $request, Refugee $refugee)
    {
        $refugee->update($request->validated());
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
        //abort_if(Gate::denies('manage_refugees_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $refugee->delete();
        return redirect()->route("manage_refugees.index");
    }
}
