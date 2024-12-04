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

        Log::info('API Request',
            [
                'datetime' => now()->toDateTimeString(),
            'user_id' => $request->user()->id ?? null,
                'application_id' => $request->header('Application-id') ?? $request->input('application_id',
                        'website'),
            'api_type' => $request->path(),
            'http_method' => $request->method(),
            'model' => null,
            'ip' => $request->ip(),
            'crew_id' => $request->user()->crew->id ?? null,
            'duration' => $duration,
            'status' => $response->status(),
            'request' => $request->all(),
                'response_content' => $response->getContent(),
                'response' => $response->status() >= 400 ? 'error' : 'success',
        ]);

        return $response;
    }

}
