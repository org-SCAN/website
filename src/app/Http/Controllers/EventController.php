<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEventRequest;
use App\Http\Requests\UpdateEventRequest;
use App\Livewire\Forms\Area;
use App\Livewire\Forms\Coordinates;
use App\Models\Event;
use App\Models\Language;
use App\Models\ListControl;
use App\Models\Translation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
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
     * @return Response
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
     * @return RedirectResponse
     */
    public function store(StoreEventRequest $request)
    {
        $event = $request->validated();
        $event["coordinates"] = Coordinates::encode($event["coordinates"]);
        $event["area"] = Area::encode($event["area"]);
        $event = Event::create($event);
        $listControl = ListControl::where("name", "Event")->first();
        Translation::handleTranslation($listControl, $event->{$listControl->key_value}, $event->{$listControl->displayed_value}, Language::defaultLanguage()->id);
        return redirect()->route("event.index");
    }

    /**
     * Display the specified resource.
     *
     * @param  Event  $event
     * @return View
     */
    public function show(Event $event)
    {
        return view("event.show", compact("event"));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Event  $event
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
     * @param  Event  $event
     * @return Response
     */
    public function update(UpdateEventRequest $request, Event $event)
    {
        $event["coordinates"] = Coordinates::encode($event["coordinates"]);
        $event["area"] = Area::encode($event["area"]);
        $event->update($request->validated());
        return redirect()->route('event.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Event  $event
     * @return RedirectResponse
     */
    public function destroy(Event $event)
    {
        $event->delete();
        return redirect()->route("event.index");
    }
}
