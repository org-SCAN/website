<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Event extends ListControl
{
    use HasFactory, Uuids, SoftDeletes;

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


    public function country()
    {
        return $this->hasOne(ListCountry::class, 'id', 'country_id');
    }

    public function type()
    {
        return $this->hasOne(ListEventType::class, 'id', 'event_type_id');
    }

    public function api_log()
    {
        return $this->hasOne(ApiLog::class, 'id', 'apiLog_id');
    }

    public function persons()
    {
        $persons_in_event = [];
        //return $this->hasManyThrough(Refugee::class, FieldRefugee::class, 'value', 'id', 'id', 'refugee_id');
        $persons = Auth::user()->crew->persons;
        // for all persons, check if one of the field value is the event id
        foreach ($persons as $person) {
            foreach ($person->fields as $field) {
                if ($field->pivot->value == $this->id) {
                    array_push($persons_in_event, $person);
                }
            }
        }
        return $persons_in_event;
    }

    public static function getAllEventsNames(){
        $events = Event::all();
        $events_name = [];
        foreach ($events as $event){
            $events_name[$event->id] = $event->name;
        }
        return $events_name;
    }
    public function crew() {
        return $this->hasOneThrough(Crew::class,
            ApiLog::class,
            "id", "id",
            "api_log",
            "crew_id");
    }
    public function user() {
        return $this->hasOneThrough(User::class,
            ApiLog::class,
            "id", "id",
            "api_log",
            "user_id");
    }
    public function getRelationsAttribute() {
        return [
            $this->fromRelation,
            $this->toRelation,
        ];
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
