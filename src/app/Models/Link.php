<?php

namespace App\Models;

use App\Traits\Uuids;
use Carbon\Carbon;
use ESolution\DBEncryption\Traits\EncryptedAttribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;


class Link extends Pivot
{
    use HasFactory, Uuids, SoftDeletes, EncryptedAttribute;

    /**
     * Give the route pattern, used in api log
     * @var string
     */
    const route_base = "links";
    /**
     *
     * This parameter allows to display the Add from and Add to section in Person.show (relation part)
     * @var bool
     */
    public static bool $quickAdd = true;
    /**
     * Indicates if the model's ID is auto-incrementing.
     *
     * @var bool
     */

    public $incrementing = false;
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
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ["from", "to", "relation_id", "date", "detail", 'api_log'];

    /**
     * The attributes that should be encrypted on save.
     *
     * @var array
     */
    protected $encryptable = [
    ];

    public static function handleApiRequest($relation)
    {

        //update or create relation

        $link = Link::updateOrCreate(
            [
                "from" => $relation["from"],
                "to" => $relation["to"],
                "relation_id" => $relation["relation_id"],
                "application_id" => $relation["application_id"]
            ],
            [
                "date" => request("date"),
                "detail" => request("detail"),
                "api_log" => request("api_log"),
            ]
        );

        /*
        $potential_link = Link::relationExists($relation["from"],
            $relation["to"],
            $relation["relation_id"],
            $relation["application_id"]);

        if ($potential_link != null) {
            $ref = $potential_link->update($relation);
        } else {
            $ref = Link::create($relation);
        }*/
        return $link;
    }

    public static function relationExists($from,
                                          $to, $relation_type,
                                          $application_id)
    {
        $potential_link = self::where("application_id",
            $application_id)->where("from",
            $from)->where("to",
            $to)->where("relation_id",
            ListRelation::getIdFromValue($relation_type))->first();

        return empty($potential_link) ? null : $potential_link;
    }

    /**
     * It returns a representative value, witch could be shown to discribe the element
     *
     * @return mixed
     */
    public function getBestDescriptiveValueAttribute()
    {
        $fromValue = $this->refugeeFrom->bestDescriptiveValue
            ?? $this->placeFrom->name
            ?? $this->eventFrom->name
            ?? null;
        $toValue = $this->refugeeTo->bestDescriptiveValue
            ?? $this->placeTo->name
            ?? $this->eventTo->name
            ?? null;

        if ($fromValue !== null && $toValue !== null) {
            return "{$fromValue} <-> {$toValue}";
        }
    }

    public
    function refugeeFrom()
    {
        return $this->belongsTo(Refugee::class,
            "from");
    }

    public
    function refugeeTo()
    {
        return $this->belongsTo(Refugee::class,
            "to");
    }

    public
    function eventFrom()
    {
        return $this->belongsTo(Event::class,
            "from");
    }

    public
    function eventTo()
    {
        return $this->belongsTo(Event::class,
            "to");
    }

    public
    function placeFrom()
    {
        return $this->belongsTo(Place::class,
            "from");
    }

    public
    function placeTo()
    {
        return $this->belongsTo(Place::class,
            "to");
    }

    public
    static function createLinks($relationFrom, $relationTo)
    {
        return self::whereRelation("$relationFrom.crew", 'crews.id', Auth::user()->crew->id)
            ->whereRelation("$relationTo.crew", 'crews.id', Auth::user()->crew->id)
            ->get();
    }

    /**
     * @return BelongsTo
     */
    public
    function relation()
    {
        return $this->belongsTo(ListRelation::class);
    }

    public
    function crew()
    {
        return $this->hasOneThrough(Crew::class,
            ApiLog::class,
            "id", "id",
            "api_log",
            "crew_id");
    }

    /**
     * Get from Id
     * @return mixed
     */
    public
    function getFromId()
    {
        return $this->attributes["from"];
    }

    /**
     * Get to Id
     * @return mixed
     */
    public
    function getToId()
    {
        return $this->attributes["to"];
    }

    public
    function getRelationWeight()
    {
        return ListRelation::find($this->getRelationId())->importance;
    }

    /**
     *
     * Get relation name
     *
     * @param $relation
     * @return mixed
     */
    public
    function getRelationId()
    {
        return $this->attributes["relation"];
    }

    /**
     * Store the relation id accorting its key or its code
     * @param $value
     */
    /*
        public function setRelationAttribute($value) {
            $this->attributes["relation"] = ListRelation::getIdFromValue($value);
        }*/

    public
    function getDateAttribute()
    {
        return Carbon::parse($this->attributes['date']);
    }

}
