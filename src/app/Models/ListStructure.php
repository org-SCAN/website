<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ListStructure extends Model
{
    use HasFactory, Uuids, SoftDeletes;

    public function listControl(){
        return $this->belongsTo(ListControl::class);
    }
}
