<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PermissionsTest extends TestCase
{
    /**
     * This class will be the parent class for all the tests that require a user with a specific permission.
     * It will test that the user can access the page and that the user can't access the page if he doesn't have the permission.
     * Checked permissions are:
     * - *.index
     * - *.show
     * - *.create
     * - *.edit
     * - *.destroy
     *
     * It will also check that the user can't access the page if he is not authenticated.
     */

    use RefreshDatabase;

    protected string $route;
    protected Model $resource;
    protected User $admin;
    protected User $null;
    protected array $run = [
        "index" => true,
        "show" => true,
        "create" => true,
        "edit" => true,
        "destroy" => true,
    ];

    /**
     * Indicates whether the default seeder should run before each test.
     *
     * @var bool
     */
    protected $seed = true;

    // set up the $admin and $null users
    public function setUp(): void {
        parent::setUp();
        $this->admin = User::whereEmail(env('DEFAULT_EMAIL'))->first();
        $this->null = User::factory()->create();
        $this->null->genToken();
    }


    /* ------------------ UNAUTHENTICATED ------------------ */

    /**
     * @brief An unauthenticated user can't see the index page
     */
    public function test_unauthenticated_user_cant_see_index_page() {
        if (!$this->run["index"]) {
            return $this->markTestSkipped('This test is not relevant for the given route.');
        }
        if (get_called_class() == 'Tests\Feature\PermissionsTest') {
            return $this->markTestSkipped('This is the parent class. It should not be tested.');
        }
        $response = $this->get($this->route);
        $response->assertRedirect('/login');
    }

    /* ------------------ INDEX ------------------ */

    /**
     * @brief An authenticated user, without '*.index' permission can't see the index page
     */

    public function test_authenticated_user_without_permission_cant_see_index_page() {
        if (!$this->run["index"]) {
            return $this->markTestSkipped('This test is not relevant for the given route.');
        }
        if (get_called_class() == 'Tests\Feature\PermissionsTest') {
            return $this->markTestSkipped('This is the parent class. It should not be tested.');
        }
        $this->actingAs($this->null);
        $response = $this->get($this->route);
        $response->assertStatus(403);
    }

    /**
     * @brief An authenticated user, with '*.index' permission can see the index page
     */

    public function test_authenticated_user_with_permission_can_see_index_page() {
        if (!$this->run["index"]) {
            return $this->markTestSkipped('This test is not relevant for the given route.');
        }
        if (get_called_class() == 'Tests\Feature\PermissionsTest') {
            return $this->markTestSkipped('This is the parent class. It should not be tested.');
        }
        $this->actingAs($this->admin);
        $response = $this->get($this->route);
        $response->assertStatus(200);
    }

    /* ------------------ SHOW ------------------ */

    /**
     * @brief An authenticated user, without '*.show' permission can't see a show page
     */

    public function test_authenticated_user_without_permission_cant_see_show_page() {
        if (!$this->run["show"]) {
            return $this->markTestSkipped('This test is not relevant for the given route.');
        }
        if (get_called_class() == 'Tests\Feature\PermissionsTest') {
            return $this->markTestSkipped('This is the parent class. It should not be tested.');
        }
        $this->actingAs($this->null);
        $response = $this->get($this->route.'/'.$this->resource->id);
        $response->assertStatus(403);
    }

    /**
     * @brief An authenticated user, with '*.show' permission can see a show page
     */

    public function test_authenticated_user_with_permission_can_see_show_page() {
        if (!$this->run["show"]) {
            return $this->markTestSkipped('This test is not relevant for the given route.');
        }
        if (get_called_class() == 'Tests\Feature\PermissionsTest') {
            return $this->markTestSkipped('This is the parent class. It should not be tested.');
        }
        $this->actingAs($this->admin);
        $response = $this->get($this->route.'/'.$this->resource->id);
        $response->assertStatus(200);
    }

    /* ------------------ CREATE ------------------ */

    /**
     * @brief An authenticated user, without '*.create' permission can't see a create page
     */

    public function test_authenticated_user_without_permission_cant_see_create_page() {
        if (!$this->run["create"]) {
            return $this->markTestSkipped('This test is not relevant for the given route.');
        }
        if (get_called_class() == 'Tests\Feature\PermissionsTest') {
            return $this->markTestSkipped('This is the parent class. It should not be tested.');
        }
        $this->actingAs($this->null);
        $response = $this->get($this->route.'/create');
        $response->assertStatus(403);
    }

    /**
     * @brief An authenticated user, with '*.create' permission can see a create page
     */

    public function test_authenticated_user_with_permission_can_see_create_page() {
        if (!$this->run["create"]) {
            return $this->markTestSkipped('This test is not relevant for the given route.');
        }
        if (get_called_class() == 'Tests\Feature\PermissionsTest') {
            return $this->markTestSkipped('This is the parent class. It should not be tested.');
        }
        $this->actingAs($this->admin);
        $response = $this->get($this->route.'/create');
        $response->assertStatus(200);
    }

    /* ------------------ EDIT ------------------ */

    /**
     * @brief An authenticated user, without '*.edit' permission can't see an edit page
     */

    public function test_authenticated_user_without_permission_cant_see_edit_page() {
        if (!$this->run["edit"]) {
            return $this->markTestSkipped('This test is not relevant for the given route.');
        }
        if (get_called_class() == 'Tests\Feature\PermissionsTest') {
            return $this->markTestSkipped('This is the parent class. It should not be tested.');
        }
        $this->actingAs($this->null);
        $response = $this->get($this->route.'/'.$this->resource->id.'/edit');
        $response->assertStatus(403);
    }

    /**
     * @brief An authenticated user, with '*.edit' permission can see an edit page
     */

    public function test_authenticated_user_with_permission_can_see_edit_page() {
        if (!$this->run["edit"]) {
            return $this->markTestSkipped('This test is not relevant for the given route.');
        }
        if (get_called_class() == 'Tests\Feature\PermissionsTest') {
            return $this->markTestSkipped('This is the parent class. It should not be tested.');
        }
        $this->actingAs($this->admin);
        $response = $this->get($this->route.'/'.$this->resource->id.'/edit');
        $response->assertStatus(200);
    }

    /* ------------------ DESTROY ------------------ */

    /**
     * @brief An authenticated user, without '*.destroy' permission can't delete a resource
     */

    public function test_authenticated_user_without_permission_cant_delete_resource() {
        if (!$this->run["destroy"]) {
            return $this->markTestSkipped('This test is not relevant for the given route.');
        }
        if (get_called_class() == 'Tests\Feature\PermissionsTest') {
            return $this->markTestSkipped('This is the parent class. It should not be tested.');
        }
        $this->actingAs($this->null);
        $response = $this->delete($this->route.'/'.$this->resource->id);
        $response->assertStatus(403);
    }

    /**
     * @brief An authenticated user, with '*.destroy' permission can delete a resource
     */

    public function test_authenticated_user_with_permission_can_delete_resource() {
        if (!$this->run["destroy"]) {
            return $this->markTestSkipped('This test is not relevant for the given route.');
        }
        if (get_called_class() == 'Tests\Feature\PermissionsTest') {
            return $this->markTestSkipped('This is the parent class. It should not be tested.');
        }
        $this->actingAs($this->admin);
        $response = $this->delete($this->route.'/'.$this->resource->id);

        $response->assertStatus(302);
        //check if the resource has been soft deleted
        $this->assertSoftDeleted($this->resource);
    }
}
