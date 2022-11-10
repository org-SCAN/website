<?php

namespace Tests;

use App\Traits\ClearProperties;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    use RefreshDatabase;
    use ClearProperties;

    /**
     * Indicates whether the default seeder should run before each test.
     *
     * @var bool
     */
    protected $seed = true;

    /**
     * Unset each property declared in this test class and its traits.
     *
     * @return void
     */
    protected function tearDown(): void {
        parent::tearDown();
        echo get_called_class()." -> usage :".memory_get_usage().PHP_EOL;
        $this->clearProperties();
        gc_collect_cycles();
        echo get_called_class()." -> usage (after clean up) :".memory_get_usage().PHP_EOL;

    }

}
