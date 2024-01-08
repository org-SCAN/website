<?php

namespace Tests\Feature;

use App\Models\Duplicate;
use App\Models\FieldRefugee;
use App\Models\Refugee;
use Illuminate\Support\Facades\Queue;

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

    /**
     * Test that a user can force duplicate compute command
     */

    public function test_user_with_permission_can_force_compute(){

        $this->actingAs($this->admin)
            ->get(route('duplicate.compute'))
            ->assertStatus(302);
    }
    /**
     * Test that a user without permission can't force duplicate compute command
     */

    public function test_user_without_permission_cant_force_compute(){

        $this->actingAs($this->null)
            ->get(route('duplicate.compute'))
            ->assertStatus(403);
    }

    /**
     * Test that the duplicate:compute artisan command is working when there were no updates in the database
     */

    public function test_duplicate_compute_command_no_update() {
        $this->test_duplicate_compute_command();

        // call the command again
        $this->artisan('duplicate:compute')
            ->assertExitCode(0)
            ->expectsOutput('Computing duplicates for crew '.$this->admin->crew->name)
            ->expectsOutputToContain('0'.' persons computed for crew '.$this->admin->crew->name);
    }

    /**
     * Test that the duplicate:compute artisan command is working
     */
    public function test_duplicate_compute_command() {
        //create some persons
        $nbPersons = 5;
        // add fields to these persons
        foreach(Refugee::factory()->count($nbPersons)->create() as $person) {
            $person->fields()->attach(FieldRefugee::random_fields());
        }

        // check that the persons are in the database
        $this->assertCount($nbPersons, Refugee::all());
        $this
            ->artisan('duplicate:compute')
            ->assertExitCode(0)
            ->expectsOutput('Computing duplicates for crew '.$this->admin->crew->name)
            ->expectsOutputToContain(($nbPersons -1).' persons computed for crew '.$this->admin->crew->name);

    }

    /**
     * Test that a warning is displayed when a user had been edited since last run
     */
    public function test_edited_person_warning_is_displayed(){
       // call the command to compute the duplicates
        $this->test_duplicate_compute_command();

        // check that the warning is not displayed
        $this->actingAs($this->admin)
            ->get(route('duplicate.index'))
            ->assertDontSee('Updated Since last run');

        // edit a person
        $person = Refugee::first();

        // mark the person as updated after the last run
        $person->updated_at = now()->addDay();
        $person->save();

        // check that the warning is displayed
        $this->actingAs($this->admin)
            ->get(route('duplicate.index'))
            ->assertSee('Updated Since last run');
    }


    /**
     * Test that a duplicate can be marked as resolved
     */

    public function test_duplicate_can_be_marked_as_resolved() {
        // call the command to compute the duplicates
        $this->test_duplicate_compute_command();

        // mark the first duplicate as resolved
        $duplicate = Duplicate::where('resolved', false)->orderByDesc("similarity")->first();

        // check that the duplicate is no more shown in the list
        $this->actingAs($this->admin)
            ->get(route('duplicate.index'))
            ->assertSee($duplicate->id);

        $this->actingAs($this->admin)
            ->get(route('duplicate.resolve', $duplicate->id))
            ->assertStatus(302);

        //check that the duplicate as been marked as resolved in the database
        $this->assertDatabaseHas('duplicates', [
            'id' => $duplicate->id,
            'resolved' => true
        ]);

        // check that the duplicate is no more shown in the list
        $this->actingAs($this->admin)
            ->get(route('duplicate.index'))
            ->assertDontSee($duplicate->id);
    }

    // check that a user without permission can't mark a duplicate as resolved

    public function test_user_without_permission_cant_mark_duplicate_as_resolved() {
        // call the command to compute the duplicates
        $this->test_duplicate_compute_command();

        // mark the first duplicate as resolved
        $duplicate = Duplicate::where('resolved', false)->orderByDesc("similarity")->first();

        // check that the duplicate is no more shown in the list
        $this->actingAs($this->null)
            ->get(route('duplicate.resolve', $duplicate->id))
            ->assertStatus(403);
    }

    /**
     * @return void
     */
    public function test_it_queues_the_duplicate_compute_job()
    {
        $this->actingAs($this->admin);
        Queue::fake();

        // Faites une requête HTTP à la route qui déclenche le job
        $response = $this->get(route('duplicate.compute'));

        // Vérifiez si la réponse est correcte (redirection, status, etc.)
        $response->assertRedirect(route('duplicate.index'));

        // Assert a job was pushed onto the default queue
        Queue::assertPushed(\App\Jobs\DuplicateComputeJob::class);
    }

    public function test_metaphone_algorithm_to_compute_duplicates() {
        //create some persons
        $nbPersons = 2;
        // add fields to these persons
        foreach(Refugee::factory()->count($nbPersons)->create() as $person) {
            $person->fields()->attach(FieldRefugee::random_fields());
        }

        // set importance of best descriptive value field
        foreach($person->fields as $field) {
            if ($field->best_descriptive_value == 1){
                $field->importance = 50;
            }
        }

        // compute duplicates
        $this
            ->artisan('duplicate:compute');

        // check that the duplicates are computed
        $this->assertCount(1, Duplicate::all());
    }

    public function test_that_an_error_message_is_seen_when_wrong_field_importance_is_set() {

        //create some persons
        $nbPersons = 2;
        // add fields to these persons
        foreach(Refugee::factory()->count($nbPersons)->create() as $person) {
            $person->fields()->attach(FieldRefugee::random_fields());
        }

        // set importance of best descriptive value field
        foreach($person->fields as $field) {
            if ($field->best_descriptive_value == 1){
                $field->importance = 1000;
            }
        }
        // compute duplicates
        $this
            ->artisan('duplicate:compute');

        // check the error message is seen
        $this->actingAs($this->admin)
            ->get(route('duplicate.compute'))
            ->assertSee('Field importance must be between 0 and 1');
    }
}
