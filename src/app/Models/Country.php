<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'countries';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = $table."__"."id";

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
    /**
     * The name of the "created at" column.
     *
     * @var string
     */
    const CREATED_AT = $table."__".'created_at';

    /**
     * The name of the "updated at" column.
     *
     * @var string
     */
    const UPDATED_AT = $table."__".'updated_at';

    /**
     * The name of the "deleted at" column.
     *
     * @var string
     */
    const DELETED_AT = $table."__".'deleted_at';

    public $incrementing = false;
}
