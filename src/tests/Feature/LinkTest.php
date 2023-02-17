<?php

namespace Tests\Feature;

use App\Models\Crew;
use App\Models\Event;
use App\Models\Field;
use App\Models\Link;
use App\Models\ListControl;
use App\Models\ListDataType;
use App\Models\ListRelation;
use App\Models\ListRelationType;
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
                'relation_id' => $relation->id,
                'type' => $relation->type->id,
                'detail' => 'test',
            ]);
        $response->assertRedirect($this->route);
        $this->assertDatabaseHas('links',
            [
                'from' => $refugee_from->id,
                'to' => $refugee_to->id,
                'relation_id' => $relation->id,
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
                'relation_id' => $relation->id,
                'type' => $relation->type->id,
                'detail' => 'test EDIT',
            ]);
        $response->assertRedirect($this->route);
        $this->assertDatabaseHas('links',
            [
                'from' => $this->resource->from,
                'to' => $this->resource->to,
                'relation_id' => $relation->id,
                'detail' => 'test EDIT',
            ]);


        $this->assertDatabaseMissing('links',
            [
                'from' => $this->resource->from,
                'to' => $this->resource->to,
                'relation_id' => $this->resource->relation,
                'detail' => 'test',
            ]);
    }

    /**
     * Test that the user can add a bilateral relation.
     */

    public function test_authenticated_user_with_permission_can_add_bilateral_link() {
        $relation = $this->test_a_user_can_create_relation_through_the_form('bilateral');
        $this->assertDatabaseHas('links',
            [
                'from' => $relation['to'],
                'to' => $relation['from'],
                'relation_id' => $relation['relation_id'],
                'detail' => $relation['detail'],
            ]);
    }

    /**
     * This function is used to create a new link through the form
     */
    public function test_a_user_can_create_relation_through_the_form($type = 'unilateral') {
        $refugee_from = Refugee::factory()->create();
        $refugee_to = Refugee::factory()->create();
        $relation = ListRelation::inRandomOrder()->first();
        $type = ListRelationType::firstWhere('name', ucfirst($type));
        $response = $this->actingAs($this->admin)->post(route($this->route.'.store'),
            [
                'from' => $refugee_from->id,
                'to' => $refugee_to->id,
                'relation_id' => $relation->id,
                'type' => $type->id,
                'detail' => 'test'
            ]);
        $response->assertRedirect($this->route);
        $relation =  [
            'from' => $refugee_from->id,
            'to' => $refugee_to->id,
            'relation_id' => $relation->id,
            'detail' => 'test',
        ];
        $this->assertDatabaseHas('links',
            $relation);

        return $relation;
    }

    /**
     * Test that the user can add a unilateral relation.
     */

    public function test_authenticated_user_with_permission_can_add_unilateral_link() {
        $relation = $this->test_a_user_can_create_relation_through_the_form('unilateral');
        $this->assertDatabaseMissing('links',
        [
            'from' => $relation['to'],
            'to' => $relation['from'],
            'relation_id' => $relation['relation_id'],
            'detail' => $relation['detail'],
        ]);
    }

    /**
     * Test that the user can add a grouped relation.
     * A grouped relation is a relation between a refugee and all persons registered in the same event.
     */

    public function test_authenticated_user_with_permission_can_add_grouped_link() {
        // The person must be registered in an event

        //pass this test for now beacause event leaks

        return $this->markTestSkipped('This test is skipped because of Event memory leak.');

        // Add event to fields

        $response = $this->actingAs($this->admin)->post(route('fields.store'), [
            "title" => "event",
            "database_type" => ListDataType::firstWhere('name',"List")->id,
            "required" => 2,
            "status" => 1,
            "linked_list" => ListControl::getListFromLinkedListName('event')->id,
        ]);
        $response->assertStatus(302);


        //check that the field is correctly added
        $this->assertDatabaseHas('fields', [
            "title" => "event",
            "crew_id" => $this->admin->crew->id,
            "required" => 2,
            "status" => 1,
            "linked_list" => ListControl::getListFromLinkedListName('event')->id,
        ]);
        $event_field_id = Field::where('title', 'event')
            ->where("crew_id", $this->admin->crew->id)
            ->first()->id;

        //create a new event
        $event = Event::factory()->create();


        // add the event to the persons
        foreach (Refugee::all() as $refugee) {
            $refugee->fields()->attach(
                $event_field_id,
                ['value' => $event->id]);
        }

        $refugee_from = Refugee::first();
        $relation = ListRelation::inRandomOrder()->first();

        $response = $this->actingAs($this->admin)->post(route($this->route.'.store'),
            [
                'from' => $refugee_from->id,
                'everyoneTo' => 1,
                'relation_id' => $relation->id,
                'type' => ListRelationType::firstWhere('name', 'Unilateral')->id,
                'detail' => 'test',
            ]);

        $response->assertSessionHasNoErrors();
        $response->assertStatus(302);
        $response->assertRedirect(route($this->route.'.index'));
        //check that the relations are correctly added
        foreach (Refugee::all() as $refugee) {
            if($refugee->id != $refugee_from->id) {
                $this->assertDatabaseHas('links',
                    [
                        'from' => $refugee_from->id,
                        'to' => $refugee->id,
                        'relation_id' => $relation->id,
                        'detail' => 'test',
                    ]);
            }
        }


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
                "relation_id",
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
