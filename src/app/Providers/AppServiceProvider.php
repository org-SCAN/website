<?php

namespace App\Providers;

use App\Services\LogHelper;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if (ENV('LOG_DATABASE_QUERIES', default: false)) {
            $this->logDatabaseQueries();
        }

    }

    private function logDatabaseQueries(): void
    {
        DB::listen(function (
            $query
        ) {

            $routeName = optional(request()->route())->getName();
            $url = request()->fullUrl();

            $logContext = LogHelper::getLogContext('database_event', 'query_executed', false);
            $logDetails = [
                'sql' => $query->sql,
                'time_ms' => $query->time,
                'route_name' => $routeName ?? 'unknown',
                'url' => $url,
            ];

            Log::info('Database Query Executed', array_merge($logContext, $logDetails));
        });
    }


}
