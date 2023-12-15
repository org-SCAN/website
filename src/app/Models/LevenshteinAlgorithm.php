<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class LevenshteinAlgorithm extends MatchingAlgorithm
{
    public function computeLevenshteinSimilarity($person1, $person2, $field_importance=1){

        $perc = 0;

        $name1 = $person1->best_descriptive_value;
        $name2= $person2->best_descriptive_value;
        similar_text($name1,$name2,$perc);
        $similarity = $perc*$field_importance;

        return($similarity);
    }

    public function computeMetaphoneSimilarity($person1, $person2)
    {
        // TODO: Implement computeMetaphoneSimilarity() method.
    }
}
