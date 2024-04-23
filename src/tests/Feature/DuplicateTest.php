<?php

namespace Tests\Feature;

use App\Models\Duplicate;
use App\Models\FieldRefugee;
use App\Models\ListMatchingAlgorithm;
use App\Models\Refugee;
use Illuminate\Support\Facades\Queue;
use App\Exceptions\Handler;
use App\Models\Field;

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
            "update" => false,
            "destroy" => false,
            "store" => false,
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

    public function test_authenticated_user_with_permission_can_mark_multiple_duplicates_as_resolved() {
        // call the command to compute the duplicates
        $this->test_duplicate_compute_command();

        // mark the first two duplicates as resolved
        $duplicates = Duplicate::where('resolved', false)->orderByDesc("similarity")->limit(2)->get();

        // check that the duplicates are no more shown in the list
        $this->actingAs($this->admin)
            ->get(route('duplicate.multiple_resolve', ['rows' => $duplicates->pluck('id')->toArray()]))
            ->assertStatus(302);

        //check that the duplicate as been marked as resolved in the database
        $this->assertDatabaseHas('duplicates', [
            'id' => $duplicates->first()->id,
            'resolved' => true
        ]);

        $this->assertDatabaseHas('duplicates', [
            'id' => $duplicates->last()->id,
            'resolved' => true
        ]);

        //check that the duplicates are no more shown in the list
        $this->actingAs($this->admin)
            ->get(route('duplicate.index'))
            ->assertDontSee($duplicates->first()->id)
            ->assertDontSee($duplicates->last()->id);
    }

    public function test_authenticated_user_without_permission_cant_mark_multiple_duplicates_as_resolved() {
        // call the command to compute the duplicates
        $this->test_duplicate_compute_command();

        // mark the first two duplicates as resolved
        $duplicates = Duplicate::where('resolved', false)->orderByDesc("similarity")->limit(2)->get();

        // check that the duplicates are no more shown in the list
        $this->actingAs($this->null)
            ->get(route('duplicate.multiple_resolve', ['rows' => $duplicates->pluck('id')->toArray()]))
            ->assertStatus(403);
    }

    public function test_authenticated_user_with_permission_can_mark_no_duplicates_as_resolved() {
        // call the command to compute the duplicates
        $this->test_duplicate_compute_command();

        // store the unresolved duplicates
        $duplicates = Duplicate::where('resolved', false)->orderByDesc("similarity")->get();

        // check that the user can mark none as duplicated
        $this->actingAs($this->admin)
            ->get(route('duplicate.multiple_resolve', null))
            ->assertStatus(302);

        // check that no duplicates have been marked as resolved
        foreach($duplicates as $duplicate) {
            $this->assertDatabaseHas('duplicates', [
                'id' => $duplicate->id,
                'resolved' => false
            ]);
        }
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

    /*public function test_that_two_persons_with_same_best_descriptive_value_have_max_similarity() {

       // create one person and replicate it
        $person = Refugee::factory()->create();
        $person->fields()->attach(FieldRefugee::random_fields());
        $person2 = $person->replicate();
        $person2->update(['id' => '49d63b05-36d7-40d7-b10c-7a8c23c41932']);
        $person2->save();

        // get parameters value
        $importance = 0;
        $count = count($person->fields);

        foreach ($person->fields as $field) {
            if ($field->best_descriptive_value == 1){
                $importance = $field->importance;
            }
        }

        // set max percentage
        $perc = 100;
        // compute similarity manually
        $similarity = $perc/$count*$importance/100;
        // call the command to compute the duplicates
        $this->artisan('duplicate:compute');

        // check that the similarity is maximum
        $duplicate = Duplicate::where('resolved', false)->orderByDesc("similarity")->first();
        $this->assertEquals($similarity,$duplicate->similarity);
    }*/

    public function test_that_two_persons_with_very_different_best_descriptive_value_have_low_similarity() {

        //create some persons
        $nbPersons = 2;

        // set parameters value
        $count = 0;
        $importance = 0;

        // add fields to these persons
        foreach(Refugee::factory()->count($nbPersons)->create() as $person) {
            $person->fields()->attach(FieldRefugee::random_fields());
            foreach ($person->fields as $field) {
                if ($field->best_descriptive_value == 1){
                    $importance = $field->importance;
                }
            }
            $count = count($person->fields);
        }

        // compute max similarity
        $perc = 100;
        $similarity = $perc/$count*$importance/100;

        // call the command to compute the duplicates
        $this->artisan('duplicate:compute');

        // check that the similarity is lesser than maximum
        $duplicate = Duplicate::where('resolved', false)->orderByDesc("similarity")->first();
        $this->assertLessThan($similarity,$duplicate->similarity);
    }

    public function test_that_an_error_message_is_seen_when_wrong_field_importance_is_set() {

        // Disable Laravel's exception handling for this test
        $this->withoutExceptionHandling();

        //create some persons
        $nbPersons = 2;
        // add fields to these persons
        foreach(Refugee::factory()->count($nbPersons)->create() as $person) {
            $person->fields()->attach(FieldRefugee::random_fields());
            // change the importance of each field
            foreach ($person->fields as $field) {
                if ($field->best_descriptive_value == 1){
                    $field->importance = 200;
                    $field->save();
                }
            }
        }

        // Expect an exception to be thrown
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Field importance must be between 0 and 1');

        // Execute the artisan command
        $this->artisan('duplicate:compute');
    }

    public function test_authenticated_user_with_permission_can_choose_duplicate_algorithm() {
        // Choose the matching algorithm
        $algorithm = ListMatchingAlgorithm::first();

        $this->actingAs($this->admin)
            ->get(route('duplicate.choose_algorithm', ['matching_algorithm_id' => $algorithm->id]))
            ->assertStatus(302);

        // Check the algorithm has been correctly selected in the user's crew
        $this->assertDatabaseHas('crews', [
            'id' => $this->admin->crew->id,
            'selected_duplicate_algorithm_id' => $algorithm->id
        ]);
    }

    public function test_authenticated_user_with_permission_cant_choose_no_duplicate_algorithm() {
        // select the algorithm
        $algorithm = ListMatchingAlgorithm::first();

        // Get the user's crew and set an algorithm
        $crew = $this->admin->crew;
        $crew->selected_duplicate_algorithm_id = $algorithm->id;
        $crew->save();

        // select a null algorithm
        $this->actingAs($this->admin)
            ->get(route('duplicate.choose_algorithm', ['matching_algorithm_id' => null]))
            ->assertStatus(302);

        // Check the algorithm has not been modified
        $this->assertDatabaseHas('crews', [
            'id' => $crew->id,
            'selected_duplicate_algorithm_id' => $algorithm->id
        ]);
    }

    public function test_user_without_permission_cant_choose_duplicate_algorithm() {
        // Choose the matching algorithm
        $algorithm = ListMatchingAlgorithm::first();

        $this->actingAs($this->null)
            ->get(route('duplicate.choose_algorithm', ['matching_algorithm_id' => $algorithm->id]))
            ->assertStatus(403);
    }

    public function test_compute_command_runs_the_selected_algorithm() {
        // Choose the matching algorithm
        $algorithm = ListMatchingAlgorithm::first();

        $this->actingAs($this->admin)
            ->get(route('duplicate.choose_algorithm', ['matching_algorithm_id' => $algorithm->id]))
            ->assertStatus(302);

        // Execute the command to compute the duplicates
        $this->test_duplicate_compute_command();

        // Check that the duplicates are displayed with the selected algorithm
        $duplicates = Duplicate::where('resolved', false)->orderByDesc("similarity")->get();

        foreach($duplicates as $duplicate) {
            $this->assertDatabaseHas('duplicates', [
                'id' => $duplicate->id,
                'resolved' => false,
                'selected_duplicate_algorithm_id' => $algorithm->id
            ]);
        }
    }

    public function test_authenticated_user_with_permission_sees_duplicates_with_selected_algorithm() {
        // Choose the matching algorithms
        $first_algorithm = ListMatchingAlgorithm::all()->first();
        $last_algorithm = ListMatchingAlgorithm::all()->last();

        // set to the first algorithm
        $this->actingAs($this->admin)
            ->get(route('duplicate.choose_algorithm', ['matching_algorithm_id' => $first_algorithm->id]))
            ->assertStatus(302);

        // Execute the command to compute the duplicates
        $this->test_duplicate_compute_command();

        // set to the last algorithm
        $this->actingAs($this->admin)
            ->get(route('duplicate.choose_algorithm', ['matching_algorithm_id' => $last_algorithm->id]))
            ->assertStatus(302);

        $this->artisan('duplicate:compute');

        $duplicates_first = Duplicate::where('resolved', false)->where('selected_duplicate_algorithm_id', $first_algorithm->id)->orderByDesc("similarity")->get();
        $duplicates_last = Duplicate::where('resolved', false)->where('selected_duplicate_algorithm_id', $last_algorithm->id)->orderByDesc("similarity")->get();

        // Check that the duplicates are displayed with the selected algorithm
        $this->actingAs($this->admin)
            ->get(route('duplicate.index'))
            ->assertSee($duplicates_last->first()->id)
            ->assertDontSee($duplicates_first->first()->id)
            ->assertSee($first_algorithm->name);
    }
}
