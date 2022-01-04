<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\SoftDeletes;

class Link extends Pivot
{
    use HasFactory, Uuids, SoftDeletes;

    /**
     * Table
     */

    protected $table = "links";
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
     * Give the route pattern, used in api log
     * @var string
     */
    const route_base = "links";

    /**
     * It returns a representative value, witch could be shown to discribe the element
     *
     * @return mixed
     */
    public function getRepresentativeValue()
    {
        return $this->from . " <-> " . $this->to;
    }

    public function refugeeFrom()
    {
        return $this->belongsTo(Refugee::class, "from");
    }

    public function refugeeTo()
    {
        return $this->belongsTo(Refugee::class, "to");
    }

    public function relation()
    {
        return $this->belongsTo(Relation::class, "relation");
    }

    /**
     *
     * Get relation name
     *
     * @param $relation
     * @return mixed
     */
    public function getRelationAttribute($relation)
    {
        $displayed_value = ListControl::where("name", "Relation")->first()->displayed_value;
        return Relation::find($relation)->$displayed_value;
    }

    /**
     *
     * Get relation name
     *
     * @param $relation
     * @return mixed
     */
    public function getRelationId()
    {
        return $this->attributes["relation"];
    }




    /**
     * Get from Id
     * @return mixed
     */
    public function getFromId()
    {
        return $this->attributes["from"];
    }


    /**
     * Get to Id
     * @return mixed
     */
    public function getToId()
    {
        return $this->attributes["to"];
    }


    public function getRelationWeight()
    {
        return Relation::find($this->getRelationId())->importance;
    }


    /**
     * Store the relation id accorting its key or its code
     * @param $value
     */

    public function setRelationAttribute($value)
    {
        $this->attributes["relation"] = Relation::getIdFromValue($value);
    }

    public static function relationExists($from, $to, $relation_type, $application_id)
    {
        $potential_link = self::where("application_id", $application_id)
            ->where("from", $from)
            ->where("to", $to)
            ->where("relation", Relation::getIdFromValue($relation_type))
            ->first();

        return empty($potential_link) ? null : $potential_link;
    }

    public static function handleApiRequest($relation)
    {

        $ref = null;
        $potential_link = Link::relationExists($relation["from"], $relation["to"], $relation["relation"], $relation["application_id"]);

        if ($potential_link != null) {
            $ref = $potential_link->update($relation);
        } else {
            $ref = Link::create($relation);
        }
        return $ref;
    }
}
