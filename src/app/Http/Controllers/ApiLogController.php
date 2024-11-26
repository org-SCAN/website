<?php

namespace App\Http\Controllers;

use App\Models\ApiLog;
use App\Services\LogViewer;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ApiLogController extends Controller
{

    protected $logViewer;
    /**
     * Create the controller instance.
     *
     * @return void
     */
    public function __construct(
        LogViewer $logViewer
    )
    {
        $this->authorizeResource(ApiLog::class, 'api_log');
        $this->logViewer = $logViewer;
    }

    /**
     * Display a listing of the resource.
     * @param  Request  $request
     *
     * @return View
     */
    public function index(
        Request $request
    )
    {
        $dates = $this->logViewer->getAvailableDates();
        $selectedDate = $request->get("date");
        $logs = $this->logViewer->getLogs($selectedDate);

        return view("api_logs.index",
            compact("logs",
                "dates",
                "selectedDate"));
    }

    /**
     * Display the specified resource.
     *
     * @param  string  $date
     * @param  string  $id
     *
     * @return View
     */
    public function show(
        $date,
        $id
    )
    {

        $api_log = $this->logViewer->getLogs($date);
        $api_log = $api_log->firstWhere('context.request_id',
            $id);

        if (!$api_log) {
            abort(404);
        }

        return view("api_logs.show", compact("api_log"));
    }

}
