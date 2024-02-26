<?php

namespace App\Http\Controllers;

use App\Models\Places;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PlacesController extends Controller
{
    public function index()
    {
        //get all places
        $places = Places::all();

        //load the view and pass the places
        return view('places.index', ['places' => $places]);
    }

    public function create()
    {
        $fields = [
            'name' => 'Name',
            'lat' => 'Latitude',
            'lon' => 'Longitude',
            'description' => 'Description',
        ];
        return view('places.create');
    }

    public function store(Request $request)
    {
        // Your store logic here
        $rules = [
            'name' => 'required|string|max:255',
            'lat' => 'required|numeric',
            'lon' => 'required|numeric',
            'description' => 'required|string',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect('places/create')
                ->withErrors($validator)
                ->withInput();
        }
        $place = new Places();
        $place->name = $request->input('name');
        $place->lat = $request->input('lat');
        $place->lon = $request->input('lon');
        $place->description = $request->input('description');
        $place->save();

        return redirect('places')->with([
            'message', 'Place created successfully!',
            'status', 'success'
        ]);
    }

    public function show(Places $places)
    {
        // Your show logic here
    }

    public function edit(Places $places)
    {
        // Your edit logic here
    }

    public function update(Request $request, Places $places)
    {
        // Your update logic here
    }

    public function destroy(Places $places)
    {
        // Your destroy logic here
    }
}
