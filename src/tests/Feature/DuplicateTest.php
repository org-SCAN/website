<?php

namespace Tests\Feature;


class DuplicateTest extends PermissionsTest
{
    /**
     * The parent class will test that the user can access the page and that the user can't access the page if he doesn't have the permission.
     */
    // set up the ids array
    public function setUp(): void
    {
        parent::setUp();
        $this->route = "duplicate";
        $this->run = [
            "index" => true,
            "show" => false,
            "create" => false,
            "edit" => false,
            "destroy" => false,
        ];
    }

}
