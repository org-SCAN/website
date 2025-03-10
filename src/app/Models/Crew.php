<?php

namespace App\Models;

use App\Traits\ModelEventsLogs;
use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Crew extends Model
{
    use HasFactory, Uuids, SoftDeletes, ModelEventsLogs;

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
     * @var string
     */
    protected $guarded = [];

    public static function getDefaultCrewId() {
        return self::where("name",
            env("DEFAULT_TEAM"))->get()->first()->id;
    }

    public function users() {
        return $this->hasMany(User::class);
    }

    public function persons() {
        return $this->hasMany(Refugee::class,
            'crew_id');
    }

    public function relations() {
        return $this->hasMany(Link::class,
            'crew_id');
    }

    public function hasEvent() {
        return $this->fields()->where('linked_list',
            ListControl::whereName('Event')->first()->id)->exists();
    }

    public function fields() {
        return $this->hasMany(Field::class);
    }

    public static function list() {
        // order by displayed value and pluck it
        $displayed_value = "name";

        return self::orderBy($displayed_value)->pluck($displayed_value,
            'id');
    }

    public function duplicate_algorithm() {
        return $this->belongsTo(ListMatchingAlgorithm::class,
            'duplicate_algorithm_id');
    }

}
