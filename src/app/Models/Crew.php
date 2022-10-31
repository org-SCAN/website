<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Crew extends Model
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

    public static function getDefaultCrewId()
    {
        $id = self::where("name", env("DEFAULT_TEAM"))->get()->first()->id;
        if (empty($id)) {
            die("No default team");
        }
        return $id;
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function fields()
    {
        return $this->hasMany(Field::class);
    }

    public function hasEvent()
    {
        return $this->fields()->where('linked_list', ListControl::whereName('Event')->first()->id)->exists();
    }
}
