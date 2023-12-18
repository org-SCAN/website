<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

abstract class MatchingAlgorithm extends Model
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
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['deleted_at', "created_at", "updated_at"];

    /**
     * Abstract method to compute Levenshtein similarity.
     *
     * @param mixed $person1
     * @param mixed $person2
     * @return float
     */
    abstract protected function computeLevenshteinSimilarity($person1, $person2);

    /**
     * Abstract method to compute Metaphone similarity.
     *
     * @param mixed $person1
     * @param mixed $person2
     * @return float
     */
    abstract protected function computeMetaphoneSimilarity($person1, $person2);


    /**
     * Define the relationship to Duplicate model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */    
    public function duplicates(){
        return $this->hasMany(Duplicate::class);
    }
}
