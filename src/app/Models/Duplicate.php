<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Duplicate extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Returns duplicated values in array : Array ( [ABC-000004] => Array ( [0] => 5decaba8-f82f-4b61-858f-bfbf133f0c4f [1] => 95904830-52a5-4fad-982e-b0eed3a5f73b ) )
     *
     * @return array
     *
     */
    public static function getDuplicates()
    {

        $duplicate_ids = DB::table("refugees")->select("unique_id")->whereNull("deleted_at")->groupBy("unique_id")->havingRaw('count(*) > 1')->pluck("unique_id");

        if (empty($duplicate_ids)) {
            return array();
        }
        return Refugee::whereIn("unique_id", $duplicate_ids)->orderBy("date", "desc")->get()->groupBy('unique_id');
    }

    public static function nextID($currentID)
    {
        $tricode = explode("-", $currentID)[0];
        $last_unique_id_used = Refugee::where("unique_id", 'like', $tricode . "-" . "%")->orderBy("unique_id", "desc")->first()->unique_id;
        $last_id = explode("-", $last_unique_id_used)[1];
        return $tricode . "-" . str_pad((intval($last_id) + 1), 6, "0", STR_PAD_LEFT);
    }


    public static function getSimilarity($duplication_users)
    {
        //die(var_dump($collection_to_parse));
        $similarities = array();
        $itemSimilarity = array();
        // [0] => { [elem0] => value00, [elem1] => value01, [elem2] => value02} , [1] => { [elem0] => value10, [elem1] => value11, [elem2] => value12} …
        foreach ($duplication_users as $userKey => $user) {
            //[elem0] => value00, [elem1] => value01, [elem2] => value02
            foreach ($user as $userItemKey => $userItemValue) {

                if ($userItemKey != "id" && $userItemKey != "uninque_id" && $userItemKey != "created_at" && $userItemKey != "updated_at" && $userItemKey != "deleted" && $userItemKey != "api_log" && $userItemKey != "application_id") {
                    foreach ($duplication_users as $user2Key => $user2) {
                        //  print("Comparing {$item["full_name"]} with {$Parseitem["full_name"]} on $itemKey <br>");
                        if (isset($user2[$userItemKey]) && !empty($user2[$userItemKey])) {
                            similar_text($userItemValue, $user2[$userItemKey], $perc);
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
     * @param integer|float $min
     * @param integer|float $max
     * @return string
     */
    public static function make_color($value, $min = 0, $max = 1)
    {
        $ratio = 1 - $value;
        if ($min > 0 || $max < 1) {
            if ($value < $min) {
                $ratio = 1;
            } else if ($value > $max) {
                $ratio = 0;
            } else {
                $range = $min - $max;
                $ratio = ($value - $max) / $range;
            }
        }

        $hue = ($ratio * 1.2) / 3.60;
        $rgb = self::hsl_to_rgb($hue, 1, .5);

        $r = round($rgb['r'], 0);
        $g = round($rgb['g'], 0);
        $b = round($rgb['b'], 0);

        return "rgb($r,$g,$b)";
    }

    protected static function hsl_to_rgb($h, $s, $l)
    {

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
        return array('r' => $r * 255.0, 'g' => $g * 255.0, 'b' => $b * 255.0);
    }
}
