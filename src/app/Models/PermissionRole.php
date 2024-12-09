<?php

namespace App\Models;

use App\Traits\ModelEventsLogs;
use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class PermissionRole extends Pivot
{
    use Uuids, HasFactory, ModelEventsLogs;

    public $incrementing = false;
    protected $table = "permission_role";

    /**
     * Indicates if the model's ID is auto-incrementing.
     *
     * @var bool
     */
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
}
