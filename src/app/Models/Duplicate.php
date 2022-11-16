<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Duplicate extends Model
{
    use HasFactory, SoftDeletes, Uuids;

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


    public static function nextID($currentID) {
        $tricode = explode("-",
            $currentID)[0];
        $last_unique_id_used = Refugee::where("unique_id",
            'like',
            $tricode."-"."%")->orderBy("unique_id",
            "desc")->first()->unique_id;
        $last_id = explode("-",
            $last_unique_id_used)[1];
        return $tricode."-".str_pad((intval($last_id) + 1),
                6, "0",
                STR_PAD_LEFT);
    }


    /**
     * @param $value
     * @param  integer|float  $min
     * @param  integer|float  $max
     * @return string
     */
    public static function make_color($value,
        $min = 0, $max = 1) {
        $ratio = 1 - $value;
        if ($min > 0 || $max < 1) {
            if ($value < $min) {
                $ratio = 1;
            } else {
                if ($value > $max) {
                    $ratio = 0;
                } else {
                    $range = $min - $max;
                    $ratio = ($value - $max) / $range;
                }
            }
        }

        $hue = ($ratio * 1.2) / 3.60;
        $rgb = self::hsl_to_rgb($hue,
            1, .5);

        $r = round($rgb['r'],
            0);
        $g = round($rgb['g'],
            0);
        $b = round($rgb['b'],
            0);

        return "rgb($r,$g,$b)";
    }

    protected static function hsl_to_rgb($h,
        $s, $l) {

        $r = $l;
        $g = $l;
        $b = $l;
        $v = ($l <= 0.5) ? ($l * (1.0 + $s)) : ($l + $s - $l * $s);
        if ($v > 0) {
            $m = $l + $l - $v;
            $sv = ($v - $m) / $v;
            $h *= 6.0;
            $sextant = floor($h);
            $fract = $h - $sextant;
            $vsf = $v * $sv * $fract;
            $mid1 = $m + $vsf;
            $mid2 = $v - $vsf;

            switch ($sextant) {
                case 0:
                    $r = $v;
                    $g = $mid1;
                    $b = $m;
                    break;
                case 1:
                    $r = $mid2;
                    $g = $v;
                    $b = $m;
                    break;
                case 2:
                    $r = $m;
                    $g = $v;
                    $b = $mid1;
                    break;
                case 3:
                    $r = $m;
                    $g = $mid2;
                    $b = $v;
                    break;
                case 4:
                    $r = $mid1;
                    $g = $m;
                    $b = $v;
                    break;
                case 5:
                    $r = $v;
                    $g = $m;
                    $b = $mid2;
                    break;
            }
        }
        return [
            'r' => $r * 255.0,
            'g' => $g * 255.0,
            'b' => $b * 255.0,
        ];
    }

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

        // foreach persons
        foreach ($persons as $person) {
            //get the fields
            $person_fields = $person->fields;
            //get all other persons
            foreach ($persons as $person2) {
                // do not compute the similarity of a person with itself
                // do not compute a couple that has already been compared

                // check if the persons have been edited since the last comparison
                $last_comparison = CommandRun::lastEnded('duplicate:compute');


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
                                $perc = 0;
                                similar_text($person_field->pivot->value,
                                    $person2_field->pivot->value,
                                    $perc);
                                $similarity += $perc;
                                $count++;
                            }
                        }
                    }
                    $similarity = $similarity / $count;
                    $similarities[$person->id][$person2->id] = $similarity;
                }
            }
        }
        return $similarities;
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
