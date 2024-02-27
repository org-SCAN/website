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
        $place = new Places();
        $validated = $request->validate($rules);
        foreach ($rules as $key => $value) {
            $place->$key = $validated[$key];
        }
        $place->save();

        return redirect('places')->with([
            'message', 'Place created successfully!',
            'status', 'success'
        ]);
    }

    public function show($id)
    {
        // Your show logic here
        $place = Places::find($id);
        return view('places.show', ['place' => $place]);
    }

    public function edit($id)
    {
        // Your edit logic here
        $place = Places::find($id);
        return view('places.edit', ['place' => $place]);
    }

    public function update(Request $request, $id)
    {
        // Your update logic here
        $rules = [
            'name' => 'required|string|max:255',
            'lat' => 'required|numeric',
            'lon' => 'required|numeric',
            'description' => 'required|string',
        ];

        $validated = $request->validate($rules);
        $place = Places::find($id);
        foreach ($rules as $key => $value) {
            $place->$key = $validated[$key];
        }
        $place->save();

        return redirect('places')->with([
            'message', 'Place updated successfully!',
            'status', 'success'
        ]);
    }

    public function destroy($id)
    {
        // Your destroy logic here
        $place = Places::find($id);
        $place->delete();

        return redirect('places')->with([
            'message', 'Place deleted successfully!',
            'status', 'success'
        ]);
    }
}
