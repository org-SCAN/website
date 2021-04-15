<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Route extends Model
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

    public function fields()
    {
        return $this->morphMany('App\Models|Field', 'get_linked_list');
    }
    public static function getLinkedList()
    {
        $elem = self::all()->toArray();
        return array_column($elem, "full", "id");
    }
}
