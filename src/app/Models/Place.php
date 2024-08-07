<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Place extends Model
{
    use HasFactory, Uuids, SoftDeletes;

    /**
     * The data type of the auto-incrementing ID.
     *
     * @var string
     */
    protected $keyType = 'string';

    /**
     * Indicates if the model's ID is auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'coordinates', 'description', 'area', 'api_log'];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    public function api_log()
    {
        return $this->hasOne(ApiLog::class, 'id', 'apiLog_id');
    }

    public static function getAllPlacesNames() {
        $places = self::all();
        $places_names = [];
        foreach ($places as $place) {
            $places_names[$place->id] = $place->name;
        }
        return $places_names;
    }
    public function crew() {
        return $this->hasOneThrough(Crew::class,
            ApiLog::class,
            "id", "id",
            "api_log",
            "crew_id");
    }
    public function toRelation() {
        return $this->belongsToMany(ListRelation::class,
            "links", "to",
            "relation_id")->using(Link::class)->wherePivotNull("deleted_at")->withPivot("from")->withPivot("id");
    }

    public function fromRelation() {
        return $this->belongsToMany(ListRelation::class,
            "links", "from",
            "relation_id")->using(Link::class)->wherePivotNull("deleted_at")->withPivot("to")->withPivot("id");
    }
}
