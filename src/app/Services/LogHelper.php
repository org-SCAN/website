<?php

namespace App\Services;

class LogHelper
{
    /**
     * Get the log context array
     *
     * @param String $tag the tag associated with the log
     * @param String $type the type of the log
     * @param bool $withAuth whether or not to include user_id and crew_id in the context
     * @return array{tag: String, type: String, request_id: string, ip: string, user_id?: string, crew_id?: string}
     */
    public static function getLogContext(String $tag, String $type, Bool $withAuth): array {
        $context = [
            'tag' => $tag,
            'type' => $type,
            'request_id' => request()->header('X-Request-ID', 'unknown'),
            'ip' => request()->ip() ?? 'unknown',
        ];
        if ($withAuth) {
            $context['user_id'] = auth()->id() ?? 'unknown';
            $context['crew_id'] = optional(auth()->user())->crew_id ?? 'unknown';
        }
        return $context;
    }
}
