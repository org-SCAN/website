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
        return config("app.log_" . $event);
    }

    /**
     * Generate the log details for a given model and event.
     *
     * @param Model $model
     * @param string $event
     * @return array
     */
    protected static function logDetails(Model $model, string $event): array
    {
        $logContext = [
            "tag" => "model_event",
            "type" => $event,
            'attribute_id' => $model->getKey(),
            'model' => get_class($model),
        ];

        if ($event === 'updated') {
            $logContext['changes'] = $model->getChanges();
        }

        return $logContext;
    }
}
