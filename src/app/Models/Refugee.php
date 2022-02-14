<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Refugee extends Model
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
     * @var mixed
     */

    /**
     * Give the route pattern, used in api log
     * @var string
     */
    const route_base = "manage_refugees";

    /**
     * The fields that describe the user.
     */
    public function fields()
    {

        $crew_id = empty(Auth::user()->crew->id) ? User::where("email", env("DEFAULT_EMAIL"))->get()->first()->crew->id : Auth::user()->crew->id;
        return $this->belongsToMany(Field::class)
            ->where("crew_id", $crew_id)
            ->withPivot("id")
            ->withPivot("value")
            ->withTimestamps()
            ->using(FieldRefugee::class)
            ->orderBy("required")
            ->orderBy("order");
    }

    /**
     * The Api log to which the refugee is associated.
     **/
    /* public function api_log()
     {
         return $this->belongsTo(ApiLog::class, "api_log");
     }
 */
    /**
     * The crew to which the refugee is associated.
     */
    public function crew()
    {
        return $this->hasOneThrough(Crew::class, ApiLog::class, "id", "id", "api_log", "crew_id");
    }

    /**
     * The user to which the refugee is associated.
     */
    public function user()
    {
        return $this->hasOneThrough(User::class, ApiLog::class, "id", "id", "api_log", "user_id");
    }

    /*
    public function relations(){
        return $this->fromRelation() + $this->toRelation();
    }
    */
    public function fromRelation()
    {
        return $this->belongsToMany(Relation::class, "links", "from", "relation")
            ->using(Link::class)
            ->wherePivotNull("deleted_at")
            ->withPivot("to")
            ->withPivot("id");
    }

    public function toRelation()
    {
        return $this->belongsToMany(Relation::class, "links", "to", "relation")
            ->using(Link::class)
            ->wherePivotNull("deleted_at")
            ->withPivot("from")
            ->withPivot("id");
    }

    public static function getAllBestDescriptiveValues()
    {
        $best_descriptive_values = [];
        foreach (self::all() as $elem) {
            $best_descriptive_values[$elem->id] = $elem->best_descriptive_value;
        }
        return $best_descriptive_values;
    }

    public static function getRefugeeIdFromReference($reference, $application_id)
    {
        $refugee = self::where("application_id", $application_id)->where('unique_id', $reference)->first();

        return !empty($refugee) ? $refugee->id : null;
    }

    public function getRepresentativeValuesAttribute()
    {
        return $this->fields->where("representative_value", 1);
    }

    public function getBestDescriptiveValueAttribute(){
        return $this->fields->where("best_descriptive_value", 1)->first()->pivot->value;
    }

    public function getRelationsAttribute(){
        return [$this->fromRelation,$this->toRelation];
    }
    public static function handleApiRequest($refugee)
    {
        $potential_refugee = Refugee::getRefugeeIdFromReference($refugee["unique_id"], $refugee["application_id"]);
        $ref = null;
        if ($potential_refugee != null) {
            $potential_refugee = self::find($potential_refugee);

            if (isset($refugee["date_update"])) {
                if ($refugee["date_update"] > $potential_refugee->updated_at) {
                    if ($potential_refugee->application_id != $refugee["application_id"]) {
                        foreach ($refugee as $field => $refugee_field) {
                            if ($potential_refugee->$refugee_field != null) {
                                unset($refugee[$refugee_field]);
                            }
                        }
                    }
                } else {
                    foreach ($refugee as $field => $refugee_field) {
                        if ($potential_refugee->$refugee_field != null) {
                            unset($refugee[$refugee_field]);
                        }
                    }
                }
                unset($refugee["date_update"]);
                $potential_refugee->update($refugee);
                $ref = true;
            } else {
                $ref = true;
            }
        } else {
            unset($refugee["date_update"]);
            Refugee::create($refugee);
            $ref = true;
        }
        return $ref;
    }
}
