<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class DatabaseQueriesLoggingTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        Config::set('app.log_database_queries', false);
    }

    /**
     * Tests that database queries are not logged when logging is disabled.
     *
     * @return void
     */
    public function test_queries_not_logged_when_disabled()
    {
        Log::shouldReceive('info')->never();

        DB::select('SELECT 1');

    }

    /**
     * Tests that database queries are logged when logging is enabled.
     *
     * @return void
     */
    public function test_queries_logged_when_enabled()
    {
        Config::set('app.log_database_queries', true);

        Log::shouldReceive('info')
            ->once()
            ->withArgs(function ($message, $context) {
                return isset($context['tag']) && $context['tag'] === 'database_event';
            });

        DB::select('SELECT 1');
    }
}
