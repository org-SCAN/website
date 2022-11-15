<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

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

    /**
     * Returns duplicated values in array : Array ( [ABC-000004] => Array ( [0] => 5decaba8-f82f-4b61-858f-bfbf133f0c4f [1] => 95904830-52a5-4fad-982e-b0eed3a5f73b ) )
     *
     * @return array
     *
     */
    public static function getDuplicates() {

        $duplicate_ids = DB::table("refugees")->select("unique_id")->whereNull("deleted_at")->groupBy("unique_id")->havingRaw('count(*) > 1')->pluck("unique_id");

        if (empty($duplicate_ids)) {
            return [];
        }
        return Refugee::whereIn("unique_id",
            $duplicate_ids)->orderBy("date",
            "desc")->get()->groupBy('unique_id');
    }

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


    public static function getSimilarity($duplication_users) {
        //die(var_dump($collection_to_parse));
        $similarities = [];
        $itemSimilarity = [];
        // [0] => { [elem0] => value00, [elem1] => value01, [elem2] => value02} , [1] => { [elem0] => value10, [elem1] => value11, [elem2] => value12} …
        foreach ($duplication_users as $userKey => $user) {
            //[elem0] => value00, [elem1] => value01, [elem2] => value02
            foreach ($user as $userItemKey => $userItemValue) {

                if ($userItemKey != "id" && $userItemKey != "unique_id" && $userItemKey != "created_at" && $userItemKey != "updated_at" && $userItemKey != "deleted" && $userItemKey != "api_log" && $userItemKey != "application_id") {
                    foreach ($duplication_users as $user2Key => $user2) {
                        //  print("Comparing {$item["full_name"]} with {$Parseitem["full_name"]} on $itemKey <br>");
                        if (isset($user2[$userItemKey]) && !empty($user2[$userItemKey])) {
                            similar_text($userItemValue,
                                $user2[$userItemKey],
                                $perc);
                            $itemSimilarity[$userKey][$user2Key][$userItemKey] = $perc / 100;
                        }

                    }
                }
            }
        }

        foreach (array_keys($duplication_users) as $user1) {
            foreach (array_keys($duplication_users) as $user2) {

                $similarities[$user1][$user2] = array_sum($itemSimilarity[$user1][$user2]) / count($itemSimilarity[$user1][$user2]);

            }
        }
        return $similarities;
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
     * @param  Crew  $crew
     */
    public static function getSimilarRefugees(Crew $crew) {
        $persons = $crew->persons;
        $similarities = [];
        $nb_op = 0;
        foreach ($persons as $person) {
            //get the fields
            $person_fields = $person->fields;
            //compare similarity of each field with the other persons
            foreach ($persons as $person2) {
                if ($person->id != $person2->id) {
                    $person2_fields = $person2->fields;
                    $similarity = 0;
                    $count = 0;
                    foreach ($person_fields as $person_field) {
                        foreach ($person2_fields as $person2_field) {
                            $nb_op++;
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
                    $similarities[$person->best_descriptive_value][$person2->best_descriptive_value] = $similarity;
                } else {
                    $nb_op++;
                }
            }
        }
        dd($nb_op);
        return $similarities;
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
                $notUpdatedSinceLastComparison = ($last_comparison == null || ($last_comparison->ended_at < $person->updated_at || $last_comparison->ended_at < $person2->updated_at));
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
}
