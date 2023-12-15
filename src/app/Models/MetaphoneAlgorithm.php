<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class MetaphoneAlgorithm extends MatchingAlgorithm
{
    public function computeMetaphoneSimilarity($person1, $person2, $field_importance=1){
        $perc = 0;
        $metaphone1 = metaphone($person1->field->best_descriptive_value);
        $metaphone2 = metaphone($person2->field->best_descriptive_value);

        $similarity = similar_text($metaphone1,$metaphone2,$perc);
        $similarity = $perc*$field_importance;

        return($similarity);
    }

    public function computeLevenshteinSimilarity($person1, $person2)
    {
        // TODO: Implement computeLevenshteinSimilarity() method.
    }
}
