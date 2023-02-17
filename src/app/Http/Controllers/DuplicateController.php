<?php

namespace App\Http\Controllers;

use App\Models\CommandRun;
use App\Models\Duplicate;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;

class DuplicateController extends Controller
{
    //constructor to check policy
    public function __construct()
    {
        $this->authorizeResource(Duplicate::class, 'duplicate');
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index() {
        $this->authorize("viewAny", Duplicate::class);


        $duplicates = Duplicate::where("crew_id",
            Auth::user()->crew_id)->where('resolved',false)->orderByDesc("similarity")->take(20)->get();


        $commandRun = CommandRun::lastEnded('duplicate:compute');

        $nextDue = CommandRun::nextDue('duplicate:compute');
        if($commandRun != null){
            $lastRun = Carbon::parse($commandRun->ended_at);
        }
        else {
            $lastRun = null;
        }

        // get the next due date for the command duplicate:compute

        return view("duplicate.index",
            compact("duplicates",
                "commandRun",
                'nextDue',
                'lastRun'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create() {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request) {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  Duplicate  $duplicate
     * @return Response
     */
    public function show(Duplicate $duplicate) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Duplicate  $duplicate
     * @return Response
     */
    public function edit(Duplicate $duplicate) {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  Duplicate  $duplicate
     * @return Response
     */
    public function update(Request $request,
        Duplicate $duplicate) {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Duplicate  $duplicate
     * @return Response
     */
    public function destroy(Duplicate $duplicate) {
        //
    }

    /**
     * This function is used to force run the duplicate command
     *
     */
    public function compute() {
        $this->authorize("compute",
            Duplicate::class);

        Artisan::queue('duplicate:compute');
        return redirect()->route('duplicate.index');
    }

    /**
     * This function is used to mark a duplicate as resolved
     *
     * @param Duplicate $duplicate
     */
    public function resolve(Duplicate $duplicate) {
        $this->authorize("resolve",
            $duplicate);

        $duplicate->resolved = true;
        $duplicate->save();
        return redirect()->route('duplicate.index');
    }
}
