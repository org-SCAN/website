<?php

namespace App\Traits;

use Illuminate\Support\Facades\Log;

trait ModelEventsLogs
{
    protected static function booted()
    {
        static::created(function ($model) {
            if (ENV("LOG_CREATE",
                default: true)) {
                Log::info("Model created", [
                    "tag" => "model_event",
                    "type" => "create",
                    "attribute_id" => $model->getKey(),
                    "model" => get_class($model),
                    "user_id" => auth()->id(),
                    "crew_id" => auth()->user()->crew_id,
                    "request_id" => request()->header("X-Request-ID",
                        "unknown"),
                ]);
            }
        });
        static::updated(function ($model) {
            if (ENV("LOG_UPDATE",
                default: true)) {
                Log::info("Model updated", [
                    "tag" => "model_event",
                    "type" => "update",
                    "attribute_id" => $model->getKey(),
                    "model" => get_class($model),
                    "changes" => $model->getChanges(),
                    "user_id" => auth()->id(),
                    "crew_id" => auth()->user()->crew_id,
                    "request_id" => request()->header("X-Request-ID",
                        "unknown"),
                ]);
            }
        });
        static::deleted(function ($model) {
            if (ENV("LOG_DELETE",
                default: true)) {
                Log::info("Model deleted", [
                    "tag" => "model_event",
                    "type" => "delete",
                    "attribute_id" => $model->getKey(),
                    "model" => get_class($model),
                    "user_id" => auth()->id(),
                    "crew_id" => auth()->user()->crew_id,
                    "request_id" => request()->header("X-Request-ID",
                        "unknown"),
                ]);
            }
        });
        static::retrieved(function ($model) {
            if (ENV("LOG_READ",
                default: false)) {
                Log::info("Model retrieved", [
                    "tag" => "model_event",
                    "type" => "retrieve",
                    "attribute_id" => $model->getKey(),
                    "model" => get_class($model),
                    "user_id" => auth()->id(),
                    "crew_id" => auth()->user()->crew_id,
                    "request_id" => request()->header("X-Request-ID",
                        "unknown"),
                ]);
            }
        });
    }
}
