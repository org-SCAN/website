<?php

namespace App\Http\Controllers;

use App\Models\Places;
use Illuminate\Http\Request;

class PlacesController extends Controller
{
    public function index()
    {
        return view('places.index');
    }

    public function create()
    {
        // Your create logic here
    }

    public function store(Request $request)
    {
        // Your store logic here
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
