<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Str;

class AutoAssignRequestId
{
    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param  Closure(Request): (Response|RedirectResponse)  $next
     * @return Response|RedirectResponse
     */
    public function handle(
        Request $request,
        Closure $next
    ) {
        if (!$request->headers->has('X-Request-ID')) {
            $request->headers->set('X-Request-ID',
                Str::uuid()->toString());
        }
        return $next($request);
    }
}
