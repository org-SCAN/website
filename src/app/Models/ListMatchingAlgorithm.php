<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ListMatchingAlgorithm extends ListControl
{
    use HasFactory, Uuids, SoftDeletes;

    protected $guarded = [];

    /**
     * Update the default algorithm in the table
     *
     * @param string $id
     * @return void
     */
    public static function updateDefault(string $id){
        $default = self::find($id);
        if($default){
            self::where('is_default', true)->update(['default' => false]);
            $default->isdefault = true;
            $default->save();
        }
    }

    /**
     * Get the default duplicate matching algorithm
     *
     * @return mixed
     */
    public static function getDefault() {
        return self::where('is_default', true)->first();
    }

    /**
     * @return HasMany
     */
    public function crews() {
        return $this->hasMany(Crew::class, 'duplicate_algorithm_id');
    }
}
