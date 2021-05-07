<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Duplicate extends Model
{
    use HasFactory;

    /**
     * Returns duplicated values in array : Array ( [ABC-000004] => Array ( [0] => 5decaba8-f82f-4b61-858f-bfbf133f0c4f [1] => 95904830-52a5-4fad-982e-b0eed3a5f73b ) )
     *
     * @return array
     *
     */
    public static function getDuplicates()
    {
        $duplicate_ids = DB::table("refugees")->select("unique_id")->groupBy("unique_id")->havingRaw('count(*) > 1')->pluck("unique_id");

        if (empty($duplicate_ids)) {
            return array();
        }
        return Refugee::select("unique_id", "id")->whereIn("unique_id", $duplicate_ids)->get()->groupBy('unique_id')->map(function ($row) {
            return $row->pluck("id"); //pluck is like array_column
        })->toArray();
    }
}
