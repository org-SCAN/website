<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Event extends ListControl
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
        return $this->hasManyThrough(Refugee::class, FieldRefugee::class, 'value', 'id', 'id', 'refugee_id');
    }
}
