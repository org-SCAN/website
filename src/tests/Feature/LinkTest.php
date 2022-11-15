<?php

namespace Tests\Feature;

use App\Models\Crew;
use App\Models\Link;
use App\Models\ListRelation;
use App\Models\Refugee;

class LinkTest extends PermissionsTest
{
    /**
     * The parent class will test that the user can access the page and that the user can't access the page if he doesn't have the permission.
     */
    // set up the ids array
    public function setUp(): void {
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
    public function test_authenticated_user_with_permission_can_see_show_page() {
        $response = $this->actingAs($this->admin)->get($this->route.'/'.$this->resource->id);
        $response->assertRedirect($this->route.'/'.$this->resource->id.'/edit');
    }

    /**
     * This function test if the user can create a new relation.
     *
     */

    public function test_authenticated_user_with_permission_can_create_link() {
        $refugee_from = Refugee::factory()->create();
        $refugee_to = Refugee::factory()->create();
        $relation = ListRelation::inRandomOrder()->first();
        $response = $this->actingAs($this->admin)->post($this->route,
            [
                'from' => $refugee_from->id,
                'to' => $refugee_to->id,
                'relation' => $relation->id,
                'detail' => 'test',
            ]);
        $response->assertRedirect($this->route);
        $this->assertDatabaseHas('links',
            [
                'from' => $refugee_from->id,
                'to' => $refugee_to->id,
                'relation' => $relation->id,
                'detail' => 'test',
            ]);
    }

    /**
     * This function test if the user can edit a relation.
     *
     */

    public function test_authenticated_user_with_permission_can_edit_link() {

        $relation = ListRelation::inRandomOrder()->first();
        $response = $this->actingAs($this->admin)->put($this->route.'/'.$this->resource->id,
            [
                'from' => $this->resource->from,
                'to' => $this->resource->to,
                'relation' => $relation->id,
                'detail' => 'test EDIT',
            ]);
        $response->assertRedirect($this->route);
        $this->assertDatabaseHas('links',
            [
                'from' => $this->resource->from,
                'to' => $this->resource->to,
                'relation' => $relation->id,
                'detail' => 'test EDIT',
            ]);


        $this->assertDatabaseMissing('links',
            [
                'from' => $this->resource->from,
                'to' => $this->resource->to,
                'relation' => $this->resource->relation,
                'detail' => 'test',
            ]);
    }


    /**
     * Test that get /api/person is working.
     * The user must have the read permission to access the page.
     * The API token must be valid
     * The response must be a 200.
     * The response must contain the persons in the crew of the user.
     */

    public function test_get_relations_api() {

        // don't need to add a relation since the setUp already created one
        // call the API
        $response = $this->withHeader('Authorization',
            'Bearer '.$this->admin->getToken())->withHeader('Application-id',
            'AppTest')->get("/api/links");
        $response->assertStatus(200);

        $response->assertJsonStructure([
            "*" => [
                "id", "date",
                "relation",
                "from", "to",
                "detail",
            ],
        ]);
        // the json should count 1 elements
        $this->assertCount(1,
            $response->json());
    }

    /**
     * Test that get /api/person is working specifying a crew.
     * The user must have the read permission to access the page.
     * The API token must be valid
     * The response must be a 200.
     * The response must contain the persons in the crew of the user.
     */

    public function test_get_relations_api_specifying_crew() {

        $crew = Crew::factory()->create();
        $response = $this->withHeader('Authorization',
            'Bearer '.$this->admin->getToken())->withHeader('Application-id',
            'AppTest')->get("/api/links/".$crew->id);
        $response->assertStatus(200);

        // check that the json is valid according to the regex

        $response->assertJsonStructure([]);

        // the json should count 0 elements
        $this->assertCount(0,
            $response->json());
    }
}
