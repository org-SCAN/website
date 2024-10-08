<?php

namespace Tests\Feature;

use App\Models\Crew;

class CrewTest extends PermissionsTest
{
    /**
     * The parent class will test that the user can access the page and that the user can't access the page if he doesn't have the permission.
     */
    // set up the ids array
    public function setUp(): void
    {
        parent::setUp();
        $this->route = "crew";
        $this->resource = Crew::factory()->create();
    }

    /**
     * Test that the store method works correctly.
     * 1. Create a new team
     *  1.1. Check that the team was created
     *  1.2. Check that the user was added to the team
     * 2. Check that it creates the GDPR field
     * 2.1. Check that the field was created
     *
     */

    public function test_store(){

        $this->actingAs($this->admin);
        $response = $this->post(route($this->route.'.store'), [
            'name' => 'test team',
        ]);

        // check that it redirects to the index page
        $response->assertRedirect(route($this->route.'.index'));

        $this->get(route($this->route.'.index'))->assertSee('test team');
        $this->assertDatabaseHas('crews', [
            'name' => 'test team',
        ]);
        $this->assertDatabaseHas('users', [
            'crew_id' => Crew::where('name', 'test team')->first()->id,
        ]);
        $this->assertDatabaseHas('fields', [
            'crew_id' => Crew::where('name', 'test team')->first()->id,
        ]);
    }

    /**
     * Test that the update method works correctly.
     * 1. Update the team
     * 1.1. Check that the team was updated
     *
     */

    public function test_update() {
        $this->actingAs($this->admin);

        $old_name = $this->resource->name;
        $new_name = 'test team2';

        $response = $this->put(route($this->route.'.update',
            $this->resource->id),
            [
                'name' => $new_name,
            ]);

        // check that it redirects to the index page
        $response->assertRedirect(route($this->route.'.index'));

        $this->get(route($this->route.'.index'))->assertSee($new_name);
        $this->assertDatabaseHas('crews',
            [
                'name' => $new_name,
            ]);
        $this->assertDatabaseMissing('crews',
            [
                'name' => $old_name,
            ]);
    }

    /**
     * Test that the add user method works correctly.
     * 1. Add a user to the team
     * 1.1. Check that the user was added to the team
     * 1.2. Check that the user was removed from the old team
     **/

    public function test_add_user() {
        $this->actingAs($this->admin);

        $user = $this->admin;
        $old_crew = $user->crew_id;
        $new_crew = $this->resource->id;

        $response = $this->put(route($this->route.'.addUser',
            $this->resource->id),
            [
                'user' => $user->id,
            ]);

        $this->get(route($this->route.'.show',
            $this->resource->id))->assertSee($user->name);
        $this->assertDatabaseHas('users',
            [
                'id' => $user->id,
                'crew_id' => $new_crew,
            ]);
        $this->assertDatabaseMissing('users',
            [
                'id' => $user->id,
                'crew_id' => $old_crew,
            ]);
    }
}
