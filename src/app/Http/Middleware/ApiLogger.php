<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ApiLogger
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $startTime = microtime(true);

        $response = $next($request);

        $duration = microtime(true) - $startTime;

        Log::channel('api')->info('API Request', [
            'user_id' => $request->user()->id ?? null,
            'application_id' => $request->header('Application-id') ?? $request->validated()[0]['application_id'] ?? 'website',
            'api_type' => $request->path(),
            'http_method' => $request->method(),
            'model' => null,
            'ip' => $request->ip(),
            'crew_id' => $request->user()->crew->id ?? null,
            'duration' => $duration,
            'status' => $response->status(),
            'request' => $request->all(),
            'response' => $response->getContent(),

        ]);
        return $response;
    }

}
