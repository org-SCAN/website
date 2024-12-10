<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

trait ModelEventsLogs
{
    /**
     * Boot the trait and register model event listeners.
     */
    protected static function booted()
    {
        foreach (['created', 'updated', 'deleted', 'retrieved'] as $event) {
            static::$event(function ($model) use ($event) {
                if (self::shouldLog($event)) {
                    Log::info("Model $event", self::logDetails($model, $event));
                }
            });
        }
    }

    /**
     * Determine if the given event should be logged based on environment configuration.
     *
     * @param string $event
     * @return bool
     */
    protected static function shouldLog(string $event): bool
    {
        return ENV("LOG_" . strtoupper($event), !($event === 'retrieved'));
    }

    /**
     * Generate the log details for a given model and event.
     *
     * @param Model $model
     * @param string $event
     * @return array
     */
    protected static function logDetails($model, string $event): array
    {
        $details = [
            'tag' => 'model_event',
            'type' => $event,
            'attribute_id' => $model->getKey(),
            'model' => get_class($model),
            'user_id' => auth()->id() ?? 'unknown',
            'crew_id' => optional(auth()->user())->crew_id ?? 'unknown',
            'request_id' => request()->header('X-Request-ID', 'unknown'),
            'ip' => request()->ip(),
        ];

        if ($event === 'updated') {
            $details['changes'] = $model->getChanges();
        }

        return $details;
    }
}
