<?php

namespace Tests\Feature;

use App\Models\Crew;
use App\Models\Role;
use App\Models\User;
use App\Models\Language;
use App\Notifications\InviteUserNotification;
use Illuminate\Support\Facades\Notification;

class UserTest extends PermissionsTest
{
    /**
     * The parent class will test that the user can access the page and that the user can't access the page if he doesn't have the permission.
     */
    // set up the ids array
    public function setUp(): void {
        parent::setUp();
        $this->route = "user";
        $this->resource = User::factory()->create();
        $this->run = [
            "index" => true,
            "show" => true,
            "create" => true,
            "store" => false,
            "update" => false,
            "edit" => true,
            "destroy" => true,
        ];
    }

    /*----------------------------------------------------------- */
    /* ---------------------------------------------------------- */
    /* ------------------ SPECIFIC PERMISSIONS ------------------ */
    /* ---------------------------------------------------------- */
    /*----------------------------------------------------------- */

    /* ------------------ changeTeam ------------------ */

    /**
     * @brief An admin can change his team from the profile section : within the profile, he should see a select with all the teams
     */

    public function test_user_with_permission_can_change_his_team_from_the_profile_section() {
        $user = $this->admin;
        $this->actingAs($user);
        $response = $this->get($this->route.'/profile');

        $response->assertStatus(200);
        $response->assertSee(__("livewire/change_crew.change_team"));

        $response = $this->post(route($this->route.'.change_team',
            $user->id), [
            'name' => Crew::factory()->create()->id,
        ]);

        $response->assertStatus(302);
        $response->assertRedirect($this->route.'/profile');

        // check that the user has been updated
        $this->assertNotNull($user->crew);
        $this->assertNotEquals($user->crew->id,
            $user->fresh()->crew->id);


    }

    /**
     * @brief A user can't change his team from the profile section : within the profile, he shouldn't see a select with all the teams
     */

    public function test_user_without_permission_cant_change_his_team_from_the_profile_section() {
        $user = $this->null;
        $this->actingAs($user);
        $response = $this->get($this->route.'/profile');

        $response->assertStatus(200);
        $response->assertDontSee('Change Team');

        $response = $this->post(route($this->route.'.change_team',
            $user->id), [
            'name' => Crew::factory()->create()->id,
        ]);

        $response->assertStatus(403);
    }

    /* ------------------ requestRole ------------------ */

    /**
     * @brief An admin can request a new role IF : he isn't the only admin
     */

    public function test_user_with_permission_and_not_the_only_admin_can_request_role_from_the_profile_section() {
        // create a new admin
        $admin = User::factory()->create();
        $admin->role_id = Role::where('name',
            'Default Admin')->first()->id;
        $admin->save();


        $user = $this->admin;
        $response = $this->accessProfile($user);

        // checks that the session has no error
        $response->assertSessionHasNoErrors();


        // check that the role request has been created in the database

        $user->refresh();
        $this->assertNotNull($user->roleRequest);


    }

    protected function accessProfile($user) {
        $this->actingAs($user);
        $response = $this->get($this->route.'/profile');

        $response->assertStatus(200);
        $response->assertSee(__("livewire/request_role.title"));

        $requested_role = Role::whereName("Default Editor")->first();

        $response = $this->post(route($this->route.'.request_role',
            $user->id), [
            'role' => $requested_role->id,
        ]);

        $response->assertStatus(302);
        $response->assertRedirect($this->route.'/profile');
        return $response;
    }

    /**
     * @brief An admin can't request a new role IF : he isn't the only admin
     */

    public function test_user_with_permission_and_the_only_admin_cant_request_role_from_the_profile_section() {

        $user = $this->admin;
        $this->actingAs($user);

        $response = $this->accessProfile($user);

        // checks that the session has no error
        $response->assertSessionHasErrors(["role"]);

    }


    /**
     * @brief An admin can change his team from the profile section : within the profile, he should see a select with all the teams
     */

    public function test_user_without_permission_cant_request_role_from_the_profile_section() {
        $user = $this->null;
        $this->actingAs($user);
        $response = $this->get($this->route.'/profile');

        $response->assertStatus(200);
        $response->assertDontSee('Request Role');

        $response = $this->post(route($this->route.'.request_role',
            $user->id), [
            'role' => Role::whereName("Default Viewer")->first()->id,
        ]);

        $response->assertStatus(403);
    }


    /**
     * @brief A user with the permission see the Re-invite button from the user.show section
     */

    public function test_user_with_permission_can_see_the_reinvite_button_from_the_user_show_section() {
        $user = $this->admin;
        $this->actingAs($user);
        $response = $this->get($this->route.'/'.$this->resource->id);

        $response->assertStatus(200);
        $response->assertSee('Re-invite');
    }

    /**
     * @brief Check that an authorized user is redirect to the profile page if he requests the same role as he already has
     */

    public function test_user_with_permission_cant_request_the_same_role_as_he_already_has() {
        $user = $this->admin;
        $this->actingAs($user);
        $response = $this->get($this->route.'/profile');

        $response->assertStatus(200);
        $response->assertSee(__("livewire/request_role.title"));

        $response = $this->post(route($this->route.'.request_role',
            $user->id), [
            'role' => $user->role->id,
        ]);

        $response->assertStatus(302);
        $response->assertRedirect($this->route.'/profile');

        // checks that the session has no error
        $response->assertSessionHasErrors(["cantRequestRole"]);
    }


    /*----------------------------------------------------------- */
    /* ---------------------------------------------------------- */
    /* ------------------  Controller Actions  ------------------ */
    /* ---------------------------------------------------------- */
    /*----------------------------------------------------------- */


    /* ------------------ grantRole & rejectRole ------------------ */

    /**
     * @brief An admin can grant a role request
     */

    public function test_user_with_permission_can_grant_role_request() {
        // create a new user with a role request

        $user = User::factory()->create();
        $requested_role = Role::whereName("Default Editor")->first();
        $this->requestRole($user,
            $requested_role);

        $response = $this->get(route($this->route.'.grant_role',
            $user->roleRequest->first()->id));

        $response->assertStatus(302);

        // checks that the session has no error
        $response->assertSessionHasNoErrors();

        // check that the role request has been created in the database

        $user->refresh();
        //the role request 'granted' field should be not null
        $this->assertNotNull($user->roleRequest->first()->granted);

        $this->assertEquals($user->role_id,
            $requested_role->id);

    }

    protected function requestRole($user,
        $requested_role) {

        $user->roleRequest()->create([
            'role_id' => $requested_role->id,
        ]);

        $this->actingAs($this->admin);
        $response = $this->get(route($this->route.'.index'));

        $response->assertStatus(200);
        $response->assertSee(__("user/index.grant_permission"));

        return $response;
    }

    /**
     * @brief An admin can grant a role request
     */

    public function test_user_with_permission_can_reject_role_request() {
        // create a new user with a role request
        $user = User::factory()->create();
        $base_role = $user->role;
        $requested_role = Role::whereName("Default Editor")->first();

        $this->requestRole($user,  $requested_role);

        $response = $this->get(route($this->route.'.reject_role',
            $user->roleRequest->first()->id));

        $response->assertStatus(302);

        // checks that the session has no error
        $response->assertSessionHasNoErrors();

        // check that the role request has been created in the database

        $user->refresh();
        //the role request 'granted' field should be not null
        $this->assertNotNull($user->roleRequest->first()->granted);

        $this->assertEquals($user->role->id,
            $base_role->id);

    }

    /**
     * @brief An unauthorized user can't grant a role request
     */

    public function test_user_without_permission_cant_grant_role_request() {
        // create a new user with a role request
        $user = User::factory()->create();


        $requested_role = Role::whereName("Default Editor")->first();
        $user->roleRequest()->create([
            'role_id' => $requested_role->id,
        ]);

        $this->actingAs($this->null);

        $response = $this->get(route($this->route.'.grant_role',
            $user->roleRequest->first()->id));

        $response->assertStatus(403);

    }


    /* ------------------ createUser ------------------ */
    /**
     * @brief Test that an admin can create a new user through the form
     * @return void
     */

    public function test_admin_can_create_user() {
        $user_to_create = [
            'name' => 'Test user',
            'email' => 'hello@test.com',
            'password' => 'Password123',
            'password_confirmation' => 'Password123',
            'team' => Crew::getDefaultCrewId(),
            'role' => Role::whereName('Default Null')->first()->id,
        ];
        $response = $this->actingAs($this->admin)->post(route('user.store'),
            $user_to_create);

        // should be redirected to the user.index page
        $response->assertRedirect(route($this->route.'.index'));

        // check that the user has been created
        $this->assertDatabaseHas('users',
            [
                //'name' => 'Test user', comment it since the name is encrypted
                'email' => 'hello@test.com',
            ]);

    }

    /**
     * @brief Test that the data validation on the user creation form
     * @return void
     */

    public function test_admin_user_creation_data_validation() {
        $user_to_create = [
            'name' => '',
            'email' => 'hello',
            'password' => 'password',
            'password_confirmation' => 'Password123',
            'team' => '',
            'role' => Role::whereName('Default Null')->first()->name,
        ];
        $response = $this->actingAs($this->admin)->post(route('user.store'),
            $user_to_create);

        // should be redirected with the following errors : name, email, password, team, role
        $response->assertSessionHasErrors([
            'name', 'email',
            'password',
            'team', 'role',
        ]);

    }



    /* ------------------ inviteUser ------------------ */

    /**
     * @brief Test that an admin can resend an invitation to a user via the show page
     */

    public function test_admin_can_resend_invitation() {

        Notification::fake();

        $user_to_invite = User::factory()->create();

        $this->actingAs($this->admin);

        $response = $this->post(route('user.invite',
            $user_to_invite->id));

        //check the redirection
        $response->assertStatus(302);


        //check that the session has a success message
        $response->assertSessionHas('inviteSuccess');

        // check that the user has been invited via notification

        Notification::assertSentTo($user_to_invite,
            InviteUserNotification::class);


    }


    /**
     * @brief Test that an admin can invite a new user through the creation form
     * @return void
     */

    public function test_admin_can_invite_user() {
        Notification::fake();

        $user_to_invite = [
            'name' => 'Test user',
            'email' => 'allo@gneugneu.gneu',
            'invite' => 1,
            'team' => Crew::getDefaultCrewId(),
            'role' => Role::whereName('Default Null')->first()->id,
        ];

        $this->actingAs($this->admin);

        //check that the admin can see the invite checkbox within the create form
        $response = $this->get(route($this->route.'.create'));
        $response->assertStatus(200);

        // check the view contains the invite checkbox within the create form
        $this->view('user.create')->assertSee('invite');


        $response = $this->post(route('user.store'),
            $user_to_invite);

        // should be redirected to the user.index page
        $response->assertRedirect(route($this->route.'.index'));

        // check that the user has been created
        $this->assertDatabaseHas('users',
            [
                // 'name' => 'Test user', name is encrypted
                'email' => 'allo@gneugneu.gneu',
            ]);

        // check that the user has been invited via notification

        Notification::assertSentTo(User::whereEmail('allo@gneugneu.gneu')->first(),
            InviteUserNotification::class);

    }


    /* ------------------ editUser ------------------ */

    /**
     * @brief Test that an admin can edit a user through the form
     * @return void
     */
    public function test_admin_can_edit_user() {
        $user = User::factory()->create();
        $user_edited = [
            'name' => 'Test user edited',
            'crew_id' => Crew::getDefaultCrewId(),
            'role_id' => Role::whereName('Default Viewer')->first()->id,
        ];

        $response = $this->actingAs($this->admin)->put(route('user.update',
            $user->id),
            $user_edited);

        // should be redirected to the user.index page
        $response->assertRedirect(route($this->route.'.index'));

        // check that the user has been edited
        $this->assertDatabaseHas('users',
            [
                // 'name' => 'Test user edited', encrypted
                'email' => $user->email,
            ]);

        // Since I use encrypted name, I can't check the name. I have to get the user and check the name
        $user = User::whereEmail($user->email)->first();
        $this->assertEquals($user->name, $user_edited['name']);
    }


    /**
     * @brief Test that an admin can't change his role if he is the only admin
     * @return void
     */

    public function test_admin_cant_change_his_role_if_he_is_the_only_admin() {
        $user = User::whereEmail(env('DEFAULT_EMAIL'))->first();
        $user_edited = [
            'name' => $user->name,
            'crew_id' => $user->crew->id,
            'role_id' => Role::whereName('Default Viewer')->first()->id,
        ];

        $response = $this->actingAs($this->admin)->put(route('user.update',
            $user->id),
            $user_edited);


        // should receive an error for role
        $response->assertSessionHasErrors([
            'role_id' => 'You are the user with the highest permissions, you can\'t change your role : you will not be able to get it back',
        ]);
    }

    /**
     * @brief Test that an admin can change his crew and his name if he is the only admin, and he does't change his role
     * @return void
     */

    public function test_admin_can_change_his_crew_and_name_if_he_is_the_only_admin() {
        $user = User::whereEmail(env('DEFAULT_EMAIL'))->first();

        // create a new crew
        $crew = Crew::factory()->create();

        $user_edited = [
            'name' => 'New admin name',
            'crew_id' => $crew->id,
            'role_id' => $user->role->id,
        ];

        $response = $this->actingAs($this->admin)->put(route('user.update',
            $user->id),
            $user_edited);

        // should be redirected to the user.index page
        $response->assertRedirect(route($this->route.'.index'));

        // check that the user has been edited
        $this->assertDatabaseHas('users',
            [
                'id' => $user->id,
                //'name' => 'New admin name',
                'email' => $user->email,
                'crew_id' => $crew->id,
                'role_id' => $user->role->id,
            ]);

        // Since I use encrypted name, I can't check the name. I have to get the user and check the name
        $user = User::whereEmail($user->email)->first();
        $this->assertEquals($user->name, $user_edited['name']);
    }


    /**
     * @brief Test that an admin can change his role if he is not the only admin
     * @return void
     */

    public function test_admin_can_change_his_role_if_he_is_not_the_only_admin() {
        // create a new admin
        $new_admin = User::factory()->create();
        $new_admin->role_id = Role::whereName('Default Admin')->first()->id;
        $new_admin->save();

        // get the default user (admin)
        $user = User::whereEmail(env('DEFAULT_EMAIL'))->first();
        $user_edited = [
            'name' => $user->name,
            'crew_id' => $user->crew->id,
            'role_id' => Role::whereName('Default Viewer')->first()->id,
            //change his role to viewer
        ];

        $response = $this->actingAs($this->admin)->put(route('user.update',
            $user->id),
            $user_edited);

        // should be redirected to the user.index page
        $response->assertRedirect(route($this->route.'.index'));

        // check that the user has been edited
        $this->assertDatabaseHas('users',
            [
                'id' => $user->id,
                //'name' => $user->name, name is encrypted
                'email' => $user->email,
                'role_id' => Role::whereName('Default Viewer')->first()->id,
            ]);
    }

    /* ------------------ deleteUser ------------------ */

    /**
     * @brief Test that an admin can't be deleted if he is the only admin
     * @return void
     */

    public function test_admin_cant_be_deleted_if_he_is_the_only_admin() {
        $response = $this->actingAs($this->admin)->delete(route('user.destroy',
            $this->admin));
        $response->assertSessionHasErrors([
            'cantDeleteUser',
        ]);
    }


    /* ------------------ viewToken ------------------ */

    /**
     * @brief Test that an admin can view his token from the profile section.
     * @return void
     */

    public function test_admin_can_view_his_token() {
        $response = $this->actingAs($this->admin)->get('/user/profile');

        // The user may have a token already, he should see the Token administration section
        $response->assertSee('Token administration');

        // The user have to click on the  Get my private API token button then enter his password to get his token

        $response->assertSee('Get my private API token');

        // WARNING IT DOESN'T TEST THE PASSWORD CHECK

        $response->assertStatus(200);
    }

    /* ------------------ changeLanguage ------------------ */
    /**
     * @brief Test that the language is updated on the site once the user changed it
     * @return void
     */
    public function test_user_can_see_the_correct_language()
    {
        //Make sure user is logged in
        $user = $this->admin;
        $this->actingAs($user);
        //Define the user's language
        $user->language_id = Language::whereLanguageName('Français')->first()->id;
        $user->save();


        //Check the language has been changed on the user page
        $response = $this->get($this->route);
        $response->assertSeeText("Demande de rôle");
        $response->assertDontSeeText('Grant user Permissions');
    }

    /**
     * @brief Test that a user is redirected to the url corresponding to their language
     * @return void
     */
    public function test_user_is_redirected_to_the_right_language_url()
    {
        //Make sure user is logged in
        $user = $this->admin;
        $this->actingAs($user);

        $response = $this->post(route($this->route.'.change_language', $user->id), [
            'language_id' => Language::whereLanguageName('Français')->first()->id,
        ]);

        //Check redirection to the right language url
        $response->assertRedirect();
        //Check the language has been changed on the user page
        $response = $this->get($this->route);
        $response->assertSeeText("Demande de rôle");
        $response->assertDontSeeText('Grant user Permissions');
    }

    /**
     * @bried Test that a user without language sees the default language (English)
     */
    public function test_user_without_language_sees_the_default_language(){
        //Make sure user is logged in
        $user = $this->admin;
        $this->actingAs($user);
        //Define the user's language
        $user->language_id = null;
        $user->save();

        $response = $this->get($this->route);
        $response->assertDontSeeText("Demande de rôle");
        $response->assertSeeText('Grant user Permissions');
    }
}
