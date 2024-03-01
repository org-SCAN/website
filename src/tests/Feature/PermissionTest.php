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

    public function test_authenticated_user_with_permission_can_see_index_page() {
        // set APP_DEBUG to true only for this test, then call the parent function
        if(!env('APP_DEBUG')) {
            putenv("APP_DEBUG=true");
        }
        parent::test_authenticated_user_with_permission_can_see_index_page();
        if(!env('APP_DEBUG')) {
            putenv("APP_DEBUG=false");
        }
    }

    }
