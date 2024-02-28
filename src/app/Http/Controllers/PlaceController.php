<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePlaceRequest;
use App\Http\Requests\UpdatePlaceRequest;
use App\Models\Place;

class PlaceController extends Controller
{
    public function index()
    {
        //get all place
        $places = Place::all();

        //load the view and pass the place
        return view('place.index', ['places' => $places]);
    }

    public function create()
    {
        return view('place.create');
    }

    public function store(StorePlaceRequest $request)
    {
        $place = $request->validated();
        $place = Place::create($place);
        $place->save();

        return redirect('place');
    }

    public function show(Place $place)
    {
        // Your show logic here
        return view('place.show', ['place' => $place]);
    }

    public function edit(Place $place)
    {
        return view('place.edit', ['place' => $place]);
    }

    public function update(UpdatePlaceRequest $request, Place $place)
    {
        $place->update($request->validated());

        return redirect()->route('place.index');
    }

    public function destroy(Place $place)
    {
        // Your destroy logic here
        $place->delete();

        return redirect('place');
    }
}
