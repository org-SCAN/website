<?php

namespace Tests\Feature;

use App\Models\Crew;
use App\Models\FieldRefugee;
use App\Models\Refugee;

class PersonTest extends PermissionsTest
{
    /**
     * The parent class will test that the user can access the page and that the user can't access the page if he doesn't have the permission.
     */
    // set up the ids array
    public function setUp(): void {
        parent::setUp();
        $this->route = "person";
        $this->resource = Refugee::factory()->create();
    }

    /**
     * Test that get /api/person is working.
     * The user must have the read permission to access the page.
     * The API token must be valid
     * The response must be a 200.
     * The response must contain the persons in the crew of the user.
     */

    public function test_get_person_api() {


        // create 10 persons (associated to the default team)
        $refugees = Refugee::factory()->count(10)->create();
        // add content to these persons
        foreach ($refugees as $refugee) {
            $refugee->fields()->attach(FieldRefugee::random_fields());
        }

        // call the API
        $response = $this->withHeader('Authorization',
            'Bearer '.$this->admin->getToken())->withHeader('Application-id',
            'AppTest')->get("/api/person");
        $response->assertStatus(200);

        // the json should count 10 elements
        $this->assertCount(10,
            $response->json());
    }

    /**
     * Test that get /api/person is working specifying a crew.
     * The user must have the read permission to access the page.
     * The API token must be valid
     * The response must be a 200.
     * The response must contain the persons in the crew of the user.
     */

    public function test_get_person_api_specifying_crew() {

        $crew = Crew::factory()->create();
        $response = $this->withHeader('Authorization',
            'Bearer '.$this->admin->getToken())->withHeader('Application-id',
            'AppTest')->get("/api/person/".$crew->id);
        $response->assertStatus(200);

        // check that the json is valid according to the regex

        $response->assertJsonStructure([]);

        // the json should count 0 elements
        $this->assertCount(0,
            $response->json());
    }
}
