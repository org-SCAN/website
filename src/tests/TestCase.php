<?php

namespace Tests;

use App\Traits\ClearProperties;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    //use RefreshDatabase;
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
        $this->clearProperties();
        gc_collect_cycles();
    }

}
