<?php

namespace App\Providers;

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
        if (config('app.log_database_queries')) {
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

            $logContext = [
                'tag' => 'database_event',
                'type' => 'query_executed',
                'sql' => $query->sql,
                'time_ms' => $query->time,
                'route_name' => $routeName ?? 'unknown',
                'url' => $url,
            ];

            Log::info('Database Query Executed', $logContext);
        });
    }


}
