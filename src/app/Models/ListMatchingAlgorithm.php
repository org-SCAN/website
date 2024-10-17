<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ListMatchingAlgorithm extends ListControl
{
    use HasFactory, Uuids, SoftDeletes;

    protected $guarded = [];
    public static function updateDefault($id){
        $default = self::find($id);
        if($default){
            self::where('is_default', true)->update(['default' => false]);
            $default->isdefault = true;
            $default->save();
        }
    }
}
