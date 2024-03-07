<?php

namespace Tests\Feature;

use App\Models\Place;

class PlaceTest extends PermissionsTest
{
    use ResourceWithCoordinatesTrait;
    /**
     * The parent class will test that the user can access the page and that the user can't access the page if he doesn't have the permission.
     */
    // set up the ids array
    public function setUp(): void {
        parent::setUp();
        $this->route = 'place';
        $this->resource = Place::factory()->create();
    }

}
