<?php

namespace App\Traits;

use Illuminate\Support\Facades\Log;

trait ModelEventsLogs
{
    protected static function booted()
    {
        static::created(function ($model) {
            if (env("LOG_CREATE", default: true)) {
                Log::info("Model created", [
                    "tag" => "model_event",
                    "type" => "create",
                    "attributes" => $model->getAttributes(),
                    "model" => get_class($model),
                    "user_id" => auth()?->id(),
                    "crew_id" => auth()?->user()->crew->id ?? null,
                    "ip" => request()->ip(),
                ]);
            }
        });
        static::updated(function ($model) {
            if (env("LOG_UPDATE", default: true)) {
                Log::info("Model updated", [
                    "tag" => "model_event",
                    "type" => "update",
                    "attributes" => $model->getAttributes(),
                    "model" => get_class($model),
                    "changes" => $model->getChanges(),
                    "user_id" => auth()?->id(),
                    "crew_id" => auth()?->user()->crew->id ?? null,
                    "ip" => request()->ip(),
                ]);
            }
        });
        static::deleted(function ($model) {
            if (env("LOG_DELETE", default: true)) {
                Log::info("Model deleted", [
                    "tag" => "model_event",
                    "type" => "delete",
                    "attributes" => $model->getAttributes(),
                    "model" => get_class($model),
                    "user_id" => auth()?->id(),
                    "crew_id" => auth()?->user()->crew->id ?? null,
                    "ip" => request()->ip(),
                ]);
            }
        });
        static::retrieved(function ($model) {
            if (env("LOG_READ", default: false)) {
                Log::info("Model retrieved", [
                    "tag" => "model_event",
                    "type" => "retrieve",
                    "attributes" => $model->getAttributes(),
                    "model" => get_class($model),
                    "user_id" => auth()?->id(),
                    "crew_id" => auth()?->user()->crew->id ?? null,
                    "ip" => request()->ip(),
                ]);
            }
        });
    }
}
