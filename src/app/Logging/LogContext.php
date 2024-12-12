<?php

namespace App\Logging;

use Illuminate\Log\Logger;

class LogContext
{
    public function __invoke(Logger $logger): void
    {
        $logger->withContext([
            'request_id' => request()->header('X-Request-ID', 'unknown'),
            'ip' => request()->ip() ?? 'unknown',
            'user_id' => auth()->id() ?? 'unknown',
            'crew_id' => optional(auth()->user())->crew_id ?? 'unknown',
        ]);
    }
}
