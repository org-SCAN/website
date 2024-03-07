<?php

namespace Tests\Feature;

use App\Models\Field;

class FieldTest extends PermissionsTest
{
    /**
     * The parent class will test that the user can access the page and that the user can't access the page if he doesn't have the permission.
     */
    // set up the ids array
    public function setUp(): void
    {
        parent::setUp();
        $this->route = "fields";
        $this->resource = Field::factory()->create();
        $this->run = [
            "index" => true,
            "show" => true,
            "create" => true,
            "store" => false,
            "edit" => true,
            "update" => false,
            "destroy" => true,
        ];
    }

    /**
     * @override Nobody can access to the delete page
     * @brief An authenticated user, with '*.destroy' permission can delete a resource
     */

    public function test_authenticated_user_with_permission_can_delete_resource()
    {
        if (get_called_class() == 'Tests\Feature\PermissionsTest') {
            return $this->markTestSkipped('This is the parent class. It should not be tested.');
        }
        $this->actingAs($this->admin);
        $response = $this->delete($this->route . '/' . $this->resource->id);

        $response->assertStatus(403);
    }
}
