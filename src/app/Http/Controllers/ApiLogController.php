<?php

namespace App\Http\Controllers;

use App\Models\ApiLog;
use Illuminate\Http\Request;

class ApiLogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $logs = ApiLog::all();
        return view("api_log.index", compact("logs"));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  String  $apiLogId
     * @return \Illuminate\Http\Response
     */
    public function show(ApiLog $apiLogId)
    {
        $log = ApiLog::find($apiLogId);
        return view("api_log.show", compact("log"));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ApiLog  $apiLog
     * @return \Illuminate\Http\Response
     */
    public function edit(ApiLog $apiLog)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ApiLog  $apiLog
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ApiLog $apiLog)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ApiLog  $apiLog
     * @return \Illuminate\Http\Response
     */
    public function destroy(ApiLog $apiLog)
    {
        //
    }
}
