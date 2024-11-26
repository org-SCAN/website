<?php

namespace App\Http\Controllers;

use App\Models\ApiLog;
use App\Services\LogViewer;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

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
     * Display a listing of the logs.
     *
     * @return Response
     */
    public function index()
    {
        $logs = $this->logViewer->getLogs();
        Log::info('Test log');
        return view('api_logs.index',
            compact('logs'));
    }

    /**
     * Display the specified log.
     *
     * @param  int  $fileIndex
     * @param  int  $lineIndex
     * @return Response
     */
    public function show(
        $fileIndex,
        $lineIndex
    )
    {
        $logs = $this->logViewer->getLogs();
        $log = $logs->where('file_index',
            $fileIndex)->where('index',
            $lineIndex)->first();

        if (!$log) {
            abort(404);
        }

        return view('api_logs.show',
            compact('log'));
    }

}
