<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Exception;

class LevenshteinAlgorithm extends MatchingAlgorithm
{
    public function computeSimilarity($person1, $person2, $field_importance)
    {
        if ($field_importance >= 0 && $field_importance <= 1) {
            $perc = 0;
            $name1 = $person1->best_descriptive_value;
            $name2 = $person2->best_descriptive_value;
            similar_text($name1, $name2, $perc);
            $similarity = $perc * $field_importance;

            return ($similarity);
        } else {
            throw new Exception("Field importance must be between 0 and 1");
        }
    }
}
