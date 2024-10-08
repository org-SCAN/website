<?php

namespace Tests\Feature;

trait ResourceWithCoordinatesTrait
{

    /**
     * Shared test method for storing a resource
     * @brief This test is used to check if the user can store a resource
     */
    public function test_authenticated_user_with_permission_can_store_resource()
    {
        if (!$this->run["store"]) {
            return $this->markTestSkipped('This test is not relevant for the given route.');
        }
        if (get_called_class() == 'Tests\Feature\PermissionsTest') {
            return $this->markTestSkipped('This is the parent class. It should not be tested.');
        }
        $this->actingAs($this->admin);
        $new_item = $this->resource->factory()->make()->toArray();
        unset($new_item["coordinates"]);
        unset($new_item["area"]);
        unset($new_item["api_log"]); // this will be set by the controller

        $new_item = $this->customizeStoreTest($new_item);

        $new_item["coordinates"]["lat"] = "10.42";
        $new_item["coordinates"]["long"] = "-5.3";
        $new_item["area"] = ["polygons" =>[[
                [
                    "lat" => "10.42",
                    "long" => "-5.3",
                ],
                [
                    "lat" => "10.42",
                    "long" => "-5.3",
                ],
                [
                    "lat" => "10.42",
                    "long" => "-5.3",
                ],
                [
                    "lat" => "10.42",
                    "long" => "-5.3",
                ],
            ]]
        ];

        $response = $this->post($this->route, $new_item);
        $response->assertStatus(302);

        // encode coordinates
        $new_item["coordinates"] = \App\Http\Livewire\Forms\Coordinates::encode($new_item["coordinates"]);
        // encode area
        $new_item["area"] = \App\Http\Livewire\Forms\Area::encode($new_item["area"]);
        $this->assertDatabaseHas($this->resource->getTable(), $new_item);
    }

    /**
     * Customizable part for each test class - override in the test class
     */
    protected function customizeStoreTest($new_item)
    {
        return $new_item;
    }


    /**
     * Shared test method for updating a resource
     */
    public function test_authenticated_user_with_permission_can_update_resource()
    {
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
        unset($new_item["area"]);
        unset($new_item["api_log"]); // this will be set by the controller

        $new_item["coordinates"]["lat"] = "10.42";
        $new_item["coordinates"]["long"] = "-5.3";
        $new_item["area"] = ["polygons" => [[
                [
                    "lat" => "10.42",
                    "long" => "-5.3",
                ],
                [
                    "lat" => "10.42",
                    "long" => "-5.3",
                ],
                [
                    "lat" => "10.42",
                    "long" => "-5.3",
                ],
                [
                    "lat" => "10.42",
                    "long" => "-5.3",
                ],
            ]]
        ];

        $new_item["id"] = $this->resource->id;

        $new_item = $this->customizeUpdateTest($new_item);

        $response = $this->put($this->route . '/' . $this->resource->id, $new_item);
        $response->assertStatus(302);
        // check if the resource has been updated ($this->resource->id contains $new_resource content and not the old one)
        $new_item["coordinates"] = \App\Http\Livewire\Forms\Coordinates::encode($new_item["coordinates"]);
        $new_item["area"] = \App\Http\Livewire\Forms\Area::encode($new_item["area"]);

        $this->assertDatabaseHas($this->resource->getTable(), $new_item);
        $this->assertDatabaseMissing($this->resource->getTable(), $this->resource->toArray());
    }

    /**
     * Customizable part for each test class - override in the test class
     */
    protected function customizeUpdateTest($new_item)
    {
        return $new_item;
    }
}
