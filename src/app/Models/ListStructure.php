<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ListStructure extends Model
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
    protected $guarded = ["associated_list"];

    public function listControl(){
        return $this->belongsTo(ListControl::class);
    }

    /**
     * The field has a data type
     */
    public function dataType() {
        return $this->hasOne(ListDataType::class, 'id', 'data_type_id');
    }

    public function list(){
        return $this->belongsToMany(ListControl::class, 'associated_lists', 'field_id', 'list_id')
            ->using(AssociatedList::class)
            ->withTimestamps()
            ->withPivot("id");
    }
}
