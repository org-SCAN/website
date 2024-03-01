<?php

namespace Tests\Feature;

use App\Models\Place;
class PlaceTest extends PermissionsTest
{
    /**
     * The parent class will test that the user can access the page and that the user can't access the page if he doesn't have the permission.
     */
    // set up the ids array
    public function setUp(): void {
        parent::setUp();
        $this->route = "place";
        $this->resource = Place::factory()->create();
    }

    /**
     * @brief An authenticated user, with '*.create' permission can use the store route to create a resource
     */

    public function test_authenticated_user_with_permission_can_store_resource() {
        if (!$this->run["store"]) {
            return $this->markTestSkipped('This test is not relevant for the given route.');
        }
        if (get_called_class() == 'Tests\Feature\PermissionsTest') {
            return $this->markTestSkipped('This is the parent class. It should not be tested.');
        }
        $this->actingAs($this->admin);
        $new_item = $this->resource->factory()->make()->toArray();
        unset($new_item["coordinates"]);
        unset($new_item["api_log"]); // this will be set by the controller

        $new_item["coordinates"]["lat"] = "10.42";
        $new_item["coordinates"]["long"] = "-5.3";
        // replace "coordinates" json with "coordinate.lat" and "coordinate.long"
        $response = $this->post($this->route, $new_item);
        $response->assertStatus(302);

        // encode coordinates
        $new_item["coordinates"] = \App\Http\Livewire\Forms\Coordinates::encode($new_item["coordinates"]);


        $this->assertDatabaseHas($this->resource->getTable(), $new_item);
    }

    /**
     * @brief An authenticated user, with '*.update' permission can use the update route to update a resource
     */

    public function test_authenticated_user_with_permission_can_update_resource() {
        if (!$this->run["update"]) {
            return $this->markTestSkipped('This test is not relevant for the given route.');
        }
        if (get_called_class() == 'Tests\Feature\PermissionsTest') {
            return $this->markTestSkipped('This is the parent class. It should not be tested.');
        }
        $this->actingAs($this->admin);
        // We need to create a new resource to update the old one
        $new_item = $this->resource->factory()->make()->toArray();
        unset($new_item["coordinates"]);
        unset($new_item["api_log"]); // this will be set by the controller

        $new_item["coordinates"]["lat"] = "10.42";
        $new_item["coordinates"]["long"] = "-5.3";
        $new_item["id"] = $this->resource->id;


        $response = $this->put($this->route.'/'.$this->resource->id, $new_item);
        $response->assertStatus(302);
        // check if the resource has been updated ($this->resource->id contains $new_resource content and not the old one)
        $new_item["coordinates"] = \App\Http\Livewire\Forms\Coordinates::encode($new_item["coordinates"]);

        $this->assertDatabaseHas($this->resource->getTable(), $new_item);
        $this->assertDatabaseMissing($this->resource->getTable(), $this->resource->toArray());

    }
}
