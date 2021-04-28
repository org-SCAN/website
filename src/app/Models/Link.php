<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Link extends Model
{
    use HasFactory, Uuids;
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
     * It returns a representative value, witch could be shown to discribe the element
     *
     * @return mixed
     */
    public function getRepresentativeValue(){
        return $this->from." <-> ".$this->to;
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
     * Get refugee1 fullname
     * @param $refugee1
     * @return mixed
     */
    public function getFromAttribute($from)
    {
        return Refugee::find($from)->full_name;
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
     * Get to fullname
     * @param $to
     * @return mixed
     */
    public function getToAttribute($to)
    {
        return Refugee::find($to)->full_name;
    }

    /**
     * Get to Id
     * @return mixed
     */
    public function getToId()
    {
        return $this->attributes["to"];
    }
}
