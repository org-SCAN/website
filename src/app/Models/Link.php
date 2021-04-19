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
     *
     * Get relation name
     *
     * @param $relation
     * @return mixed
     */
    public function getRelationAttribute($relation)
    {
        return Relation::find($relation)->name; //TODO : ne pas passer par role mais trouver un moyen de le contourner en passant par control_list (maybe en storant la clé de la liste dans les config ?)
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
        return $this->attributes["relation"]; //TODO : ne pas passer par role mais trouver un moyen de le contourner en passant par control_list (maybe en storant la clé de la liste dans les config ?)
    }

    /**
     * Get refugee1 fullname
     * @param $refugee1
     * @return mixed
     */
    public function getRefugee1Attribute($refugee1)
    {
        return Refugee::find($refugee1)->full_name;
    }


    /**
     * Get refugee1 Id
     * @param $refugee1
     * @return mixed
     */
    public function getRefugee1Id()
    {
        return $this->attributes["refugee1"];
    }

    /**
     * Get refugee2 fullname
     * @param $refugee1
     * @return mixed
     */
    public function getRefugee2Attribute($refugee2)
    {
        return Refugee::find($refugee2)->full_name;
    }

    /**
     * Get refugee2 Id
     * @param $refugee1
     * @return mixed
     */
    public function getRefugee2Id()
    {
        return $this->attributes["refugee2"];
    }
}
