<?php

namespace Tests\Feature;

use App\Models\ApiLog;
use App\Models\Refugee;


class ApiLogTest extends PermissionsTest
{
    /**
     * The parent class will test that the user can access the page and that the user can't access the page if he doesn't have the permission.
     */
    // set up the ids array
    public function setUp(): void
    {
        parent::setUp();
        $this->route = "api_logs";
        $refugee = Refugee::factory()->create();
        $this->resource = ApiLog::find($refugee->api_log);
        $this->run = [
            "index" => true,
            "show" => true,
            "create" => false,
            "edit" => false,
            "destroy" => false,
        ];
    }
}
