<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class ListDataType extends ListControl
{
    use HasFactory, Uuids, SoftDeletes;
    /**
     * The default datatype name
     *
     * @var string
     */
    protected static $default = "Short text";
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

    /**
     * Return the default datatype
     * @return ListDataType
     */
    public static function default(){
        return self::firstWhere('name', self::$default);
    }
}
