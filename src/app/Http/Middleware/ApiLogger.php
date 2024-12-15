<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class ApiLogger
{
    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param  Closure(Request): (Response|RedirectResponse)  $next
     * @return Response|RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $startTime = microtime(true);

        $response = $next($request);

        $duration = microtime(true) - $startTime;

        $logContext = [
            'tag' => 'api_request',
            'type' => 'api_request',
            'application_id' => $request->header('Application-id') ?? $request->input('application_id',
                    'website'),
            'api_type' => $request->path(),
            'http_method' => $request->method(),
            'duration' => $duration,
            'status' => $response->status(),
            'request' => $request->all(),
            'response' => $response->status() >= 400 ? 'error' : 'success',
        ];
        Log::info('API Request', $logContext);

        return $response;
    }

}
