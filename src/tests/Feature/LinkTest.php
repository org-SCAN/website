<?php

namespace Tests\Feature;

use App\Models\Link;
use App\Models\Refugee;

class LinkTest extends PermissionsTest
{
    /**
     * The parent class will test that the user can access the page and that the user can't access the page if he doesn't have the permission.
     */
    // set up the ids array
    public function setUp(): void
    {
        parent::setUp();
        $this->route = "links";
        // Create 5 refugees
        Refugee::factory()->count(5)->create();
        $this->resource = Link::factory()->create();
    }

    /**
     * @override authenticated_user_with_permission_can_see_show_page
     *
     * -> links.show should redirect to links.edit
     */
    public function test_authenticated_user_with_permission_can_see_show_page()
    {
        $response = $this->actingAs($this->admin)->get($this->route . '/' . $this->resource->id);
        $response->assertRedirect($this->route . '/' . $this->resource->id . '/edit');
    }
}
