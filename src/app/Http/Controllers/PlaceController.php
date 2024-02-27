<?php

namespace App\Http\Controllers;

use App\Models\Place;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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

    public function store(Request $request)
    {
        // Your store logic here
        $rules = [
            'name' => 'required|string|max:255',
            'lat' => 'required|numeric',
            'lon' => 'required|numeric',
            'description' => 'required|string',
        ];
        $place = new Place();
        $validated = $request->validate($rules);
        foreach ($rules as $key => $value) {
            $place->$key = $validated[$key];
        }
        $place->save();

        return redirect('place')->with([
            'message', 'Place created successfully!',
            'status', 'success'
        ]);
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
        $place = Place::find($id);
        foreach ($rules as $key => $value) {
            $place->$key = $validated[$key];
        }
        $place->save();

        return redirect('place')->with([
            'message', 'Place updated successfully!',
            'status', 'success'
        ]);
    }

    public function destroy($id)
    {
        // Your destroy logic here
        $place = Place::find($id);
        $place->delete();

        return redirect('place')->with([
            'message', 'Place deleted successfully!',
            'status', 'success'
        ]);
    }
}
