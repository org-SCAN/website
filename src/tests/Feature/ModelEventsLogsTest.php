<?php

namespace Tests\Feature;

use App\Traits\ModelEventsLogs;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class ModelEventsLogsTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        // Disable all logs by default
        foreach (['created', 'updated', 'deleted', 'retrieved'] as $event) {
            Config::set("app.log_{$event}", false);
        }
        Config::set("app.log_database_queries", false);
    }

    public function test_created_event_logging()
    {
        $triggerCreated = function () {
            $model = new FakeModel();
            $model->simulateEvent('created');
        };

        $this->runEventWithLogDisabled('created', $triggerCreated);
        $this->runEventWithLogEnabled('created', $triggerCreated);
    }

    public function test_updated_event_logging()
    {
        $triggerUpdated = function () {
            $model = new FakeModel();
            $model->exists = true;
            $model->simulateEvent('updated');
        };

        $this->runEventWithLogDisabled('updated', $triggerUpdated);
        $this->runEventWithLogEnabled('updated', $triggerUpdated);
    }

    public function test_deleted_event_logging()
    {
        $triggerDeleted = function () {
            $model = new FakeModel();
            $model->exists = true;
            $model->simulateEvent('deleted');
        };

        $this->runEventWithLogDisabled('deleted', $triggerDeleted);
        $this->runEventWithLogEnabled('deleted', $triggerDeleted);
    }

    public function test_retrieved_event_logging()
    {
        $triggerRetrieved = function () {
            $model = new FakeModel();
            $model->exists = true;
            $model->simulateEvent('retrieved');
        };

        $this->runEventWithLogDisabled('retrieved', $triggerRetrieved);
        $this->runEventWithLogEnabled('retrieved', $triggerRetrieved);
    }

    private function runEventWithLogDisabled(string $event, callable $triggerCallback): void
    {
        // Disable logging for this event
        Config::set("app.log_{$event}", false);
        $this->assertFalse($this->callShouldLog($event), "$event should return false when disabled");

        // Expect no log call
        Log::shouldReceive('info')->never();

        $triggerCallback();
    }

    private function runEventWithLogEnabled(string $event, callable $triggerCallback): void
    {
        // Enable logging for this event
        Config::set("app.log_{$event}", true);
        $this->assertTrue($this->callShouldLog($event), "$event should return true when enabled");

        Log::shouldReceive('info')
            ->once()
            ->withArgs(function ($message, $context) use ($event) {
                return isset($context['type']) && $context['type'] === $event;
            });

        $triggerCallback();
    }

    private function callShouldLog(string $event): bool
    {
        $mock = new class {
            use ModelEventsLogs;

            public function callShouldLog(string $event)
            {
                return self::shouldLog($event);
            }
        };

        return $mock->callShouldLog($event);
    }
}


/**
 * A fake Eloquent model applying the ModelEventsLogs trait.
 * We add a method to simulate model events without actually using the database.
 */
class FakeModel extends Model
{
    use ModelEventsLogs;

    // No table or timestamps required for this test
    public $timestamps = false;

    /**
     * Simulate firing a model event.
     * E.g. $this->simulateEvent('created');
     */
    public function simulateEvent(string $event): void
    {
        $this->fireModelEvent($event, false);
    }
}
