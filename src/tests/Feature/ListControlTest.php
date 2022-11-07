<?php

namespace Tests\Feature;

use App\Models\ListControl;

class ListControlTest extends PermissionsTest
{
    /**
     * The parent class will test that the user can access the page and that the user can't access the page if he doesn't have the permission.
     */
    // set up the ids array
    public function setUp(): void
    {
        parent::setUp();
        $this->route = "lists_control";
        // Use a pre-existing list control
        $this->resource = ListControl::inRandomOrder()->first();
    }


    /**
     * @override Since I use a pre-existing list control, I need to override the test_unauthenticated_user_cant_see_delete_resource method
     * Indeed, the user can't delete the resource -> he should receive an error with this message: 'You can't delete this element since it's used in at least one field.'
     *
     */

    public function test_authenticated_user_with_permission_can_delete_resource()
    {
        if (get_called_class() == 'Tests\Feature\PermissionsTest') {
            return $this->markTestSkipped('This is the parent class. It should not be tested.');
        }
        $this->actingAs($this->admin);
        $response = $this->delete($this->route . '/' . $this->resource->id);

        $response->assertStatus(302);

        $response->assertSessionHasErrors('deleteList');
    }


    /**
     * TODO : create a new list (using the form) and check that the list is created
     * TODO : check that the list is created in the database
     * TODO : check that the list can be deleted (since it's not used)
     */

}
