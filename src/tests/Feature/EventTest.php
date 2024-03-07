<?php

namespace Tests\Feature;

use App\Models\Event;


class EventTest extends PermissionsTest
{
    use ResourceWithCoordinatesTrait;
    /**
     * The parent class will test that the user can access the page and that the user can't access the page if he doesn't have the permission.
     */
    // set up the ids array
    public function setUp(): void {
        parent::setUp();
        $this->route = 'event';
        $this->resource = Event::factory()->create();
    }

    /**
     * @param $new_item
     * @return mixed
     */
    protected function customizeStoreTest($new_item){
        $new_item["event_subtype_id"] = null;
        return $new_item;
    }

    /**
     * @param $new_item
     * @return mixed
     */
    protected function customizeUpdateTest($new_item) {
        $new_item["event_subtype_id"] = $this->resource->event_subtype_id; // subtype is not available yet
        return $new_item;
    }
}
