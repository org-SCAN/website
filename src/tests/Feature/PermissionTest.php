<?php

namespace Tests\Feature;

use App\Models\Permission;

class PermissionTest extends PermissionsTest
{
    /**
     * The parent class will test that the user can access the page and that the user can't access the page if he doesn't have the permission.
     */
    // set up the ids array
    public function setUp(): void
    {
        parent::setUp();
        $this->route = "permissions";
        $this->run = [
            "index" => true,
            "show" => false,
            "create" => false,
            "store" => false,
            "edit" => false,
            "update" => false,
            "destroy" => false,
            ];
        // get the fist permission
        $this->resource = Permission::first();
    }


    }
