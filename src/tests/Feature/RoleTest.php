<?php

namespace Tests\Feature;

use App\Models\Role;

class RoleTest extends PermissionsTest
{
    /**
     * The parent class will test that the user can access the page and that the user can't access the page if he doesn't have the permission.
     */
    // set up the ids array
    public function setUp(): void
    {
        parent::setUp();
        $this->route = "roles";
        $this->resource = Role::factory()->create();
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
        $new_item = [
         "role" => [
             "name" => "role_creation_test"
             ]
        ];
        $response = $this->post($this->route, $new_item);
        $response->assertStatus(302);
        $new_item_check["name"] = $new_item["role"]["name"];
        $this->assertDatabaseHas($this->resource->getTable(), $new_item_check);
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
        $new_resource = [
            "role" => [
                "name" => "role_creation_test"
            ]
        ];

        $response = $this->put($this->route.'/'.$this->resource->id, $new_resource);
        $response->assertStatus(302);

        $new_resource_check["name"] = $new_resource["role"]["name"];
        // check if the resource has been updated ($this->resource->id contains $new_resource content and not the old one)
        $this->assertDatabaseHas($this->resource->getTable(), $new_resource_check);
        $this->assertDatabaseMissing($this->resource->getTable(), $this->resource->toArray());

    }
}
