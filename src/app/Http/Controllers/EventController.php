<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEventRequest;
use App\Http\Requests\UpdateEventRequest;
use App\Models\ApiLog;
use App\Models\Event;
use Illuminate\View\View;

class EventController extends Controller
{
    /**
     * Create the controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->authorizeResource(Event::class, 'event');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $events = Event::all();
        return view("event.index", compact("events"));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return View
     */
    public function create()
    {
        return view("event.create");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\StoreEventRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreEventRequest $request)
    {
        $log = ApiLog::createFromRequest($request, 'Event');
        $event = $request->validated();
        $event["api_log"] = $log->id;
        Event::create($event);
        return redirect()->route("event.index");
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Event $event
     * @return View
     */
    public function show(Event $event)
    {
        return view("event.show", compact("event"));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Event $event
     * @return View
     */
    public function edit(Event $event)
    {
        return view("event.edit", compact("event"));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\UpdateEventRequest $request
     * @param \App\Models\Event $event
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateEventRequest $request, Event $event)
    {
        $event->update($request->validated());
        return redirect()->route('event.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Event $event
     * @return \Illuminate\Http\Response
     */
    public function destroy(Event $event)
    {
        //
    }
}
