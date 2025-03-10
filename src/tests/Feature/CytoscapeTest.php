<?php

namespace Tests\Feature;

use App\Models\Link;
use App\Models\Refugee;

class CytoscapeTest extends PermissionsTest
{
    /**
     * The parent class will test that the user can access the page and that the user can't access the page if he doesn't have the permission.
     */
    // set up the ids array
    public function setUp(): void
    {
        parent::setUp();
        $this->route = "cytoscape";
        $this->run = [
            "index" => true,
            "show" => false,
            "create" => false,
            "edit" => false,
            "update" => false,
            "destroy" => false,
            "store" => false,
        ];
    }

    /**
     * Test the index page of the Cytoscape but add relation to the DB first
     */
    public function test_index_page_with_relations(){
        // Generate refugees
        Refugee::factory()->count(5)->create();
        // Generate links
        Link::factory()->count(5)->create();
        // Check that the user can access the page
        $this->actingAs($this->admin)->get(route($this->route . ".index"))->assertStatus(200);

    }
}
