<?php

namespace App\Http\Controllers;

use App\Models\Duplicate;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class DuplicateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $duplicates = Duplicate::getDuplicates();
        return view("duplicate.index", compact("duplicates"));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param Duplicate $duplicate
     * @return Response
     */
    public function show(Duplicate $duplicate)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Duplicate $duplicate
     * @return Response
     */
    public function edit(Duplicate $duplicate)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Duplicate $duplicate
     * @return Response
     */
    public function update(Request $request, Duplicate $duplicate)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Duplicate $duplicate
     * @return Response
     */
    public function destroy(Duplicate $duplicate)
    {
        //
    }
}
