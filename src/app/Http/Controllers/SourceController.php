<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSourceRequest;
use App\Http\Requests\UpdateSourceRequest;
use App\Models\Source;

class SourceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->authorizeResource(Source::class, 'source');
    }


    public function index()
    {
        $sources = Source::all();
        return view('sources.index', compact('sources'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\StoreSourceRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreSourceRequest $request)
    {
        $source = Source::create($request->validated());
        return redirect()->route('source.show', $source);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('sources.create');
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Source $source
     * @return \Illuminate\Http\Response
     */
    public function show(Source $source)
    {
        return view('sources.show', compact('source'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Source $source
     * @return \Illuminate\Http\Response
     */
    public function edit(Source $source)
    {
        return view('sources.edit', compact('source'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\UpdateSourceRequest $request
     * @param \App\Models\Source $source
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateSourceRequest $request, Source $source)
    {
        $source->update($request->validated());
        return redirect()->route('source.show', $source);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Source $source
     * @return \Illuminate\Http\Response
     */
    public function destroy(Source $source)
    {
        $source->delete();
        return redirect()->route('source.index');
    }
}
