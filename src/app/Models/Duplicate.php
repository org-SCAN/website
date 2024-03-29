<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Duplicate extends Model
{
    use SoftDeletes, Uuids;

    /**
     * Indicates if the model's ID is auto-incrementing.
     *
     * @var bool
     */

    public $incrementing = false;

    /**
     * The data type of the auto-incrementing ID.
     *
     * @var string
     */

    protected $keyType = 'string';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */

    protected $guarded = [];

    /**
     * This function is used to get similar persons. It compares the similarity of the persons based on the fields that are completed.
     * It computes the similarity of each field and then averages the similarity of each field to get the similarity of the persons for a given team.
     * It must not compute the similarity of a person with itself.
     * It must not compute the similarity of a person with a person that has already been compared. (eg A -> B is the same as B -> A)
     * It must not compute the similarity of a couple that has already been compared and not edited since.
     * It must not compute the similarity of a couple marked as "not similar" (ie resolve) by the user.
     *
     * @param  Crew  $crew
     */

    public static function compute(Crew $crew) {
        $persons = $crew->persons;
        $similarities = [];

        $selected_duplicate_algorithm = ListMatchingAlgorithm::find($crew->selected_duplicate_algorithm_id);
        $algorithm_id = $selected_duplicate_algorithm->id;
        $algorithm_model = $selected_duplicate_algorithm->model;

        $last_comparison = CommandRun::lastEnded('duplicate:compute');
        // foreach persons

        // get matching algorithm
        $algorithm = $algorithm_model();

        foreach ($persons as $person) {
            //get the fields
            $person_fields = $person->fields;
            //get all other persons
            foreach ($persons as $person2) {
                // do not compute the similarity of a person with itself
                // do not compute a couple that has already been compared

                // check if the persons have been edited since the last comparison


                // compare the updated_at date of the persons with the last comparison date
                // if the persons have been edited since the last comparison, then compute the similarity
                // else, do not compute the similarity

                $notSamePerson = $person->id != $person2->id;
                $notAlreadyCompared = !isset($similarities[$person2->id][$person->id]);
                $notUpdatedSinceLastComparison = ($last_comparison == null || ($last_comparison->started_at < $person->updated_at || $last_comparison->started_at < $person2->updated_at));
                $notResolved = !self::isResolved($person,
                    $person2);

                if ($notSamePerson && $notAlreadyCompared && $notUpdatedSinceLastComparison && $notResolved) {
                    $person2_fields = $person2->fields;
                    $similarity = 0;
                    $count = 0;
                    foreach ($person_fields as $person_field) {
                        foreach ($person2_fields as $person2_field) {
                            if ($person_field->id == $person2_field->id) {
                                if($person_field->best_descriptive_value == 1){
                                    $similarity += $algorithm->computeSimilarity($person, $person2, $person_field->importance / 100);
                                }
                                $count++;
                            }
                        }
                    }
                    $similarity = $count> 0 ? $similarity / $count : null;
                    $similarities[$person->id][$person2->id] = $similarity;
                }
            }
        }
        return [$similarities, $algorithm_id];
    }

    /**
     * This function is used to check if a couple of persons has been marked as "not similar" (ie resolve) by the user.
     * @param  Refugee  $person
     * @param  Refugee  $person2
     * @return boolean
     */

    public static function isResolved(Refugee $person,
        Refugee $person2) {
        $resolve = Duplicate::select(['resolved'])->where('person1_id',
            $person->id)->where('person2_id',
            $person2->id)->first();

        if ($resolve != null && $resolve->resolved == 1) {
            return true;
        }
        $resolve = Duplicate::select(['resolved'])->where('person1_id',
            $person2->id)->where('person2_id',
            $person->id)->first();

        if ($resolve != null && $resolve->resolved == 1) {
            return true;
        }
        return false;
    }

    /**
     * Relation with crew
     * @return BelongsTo
     */
    public function crew() {
        return $this->belongsTo(Crew::class);
    }

    /**
     * Relation with person1
     * @return BelongsTo
     */
    public function person1() {
        return $this->belongsTo(Refugee::class,
            'person1_id');
    }

    /**
     * Relation with person2
     * @return BelongsTo
     */
    public function person2() {
        return $this->belongsTo(Refugee::class,
            'person2_id');
    }

    /**
     * Relation with command run
     * @return BelongsTo
     */
    public function commandRun() {
        return $this->belongsTo(CommandRun::class);
    }
}
