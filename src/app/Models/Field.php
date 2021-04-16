<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuids;

class Field extends Model
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
     * Get all of the owning linked_list models.
     */
    public function get_linked_list()
    {
        return $this->morphTo();
    }

    public function getStatusAttribute($value){
        switch ($value) {
            case 0:
                $text_status = "Disabled";
                break;
            case 1:
                $text_status = "Website";
                break;
            case 2:
                $text_status = "Website & App";
                break;
            default:
                $text_status = "Undefined";
        }
        return $text_status;
    }

    public function getRequiredAttribute($value){
        switch ($value) {
            case 0:
                $text_required = "Auto generated";
                break;
            case 1:
                $text_required = "Required";
                break;
            case 2:
                $text_required = "Strongly advised";
                break;
            case 3:
                $text_required = "Advised";
                break;
            case 4:
                $text_required = "If possible";
                break;
            default:
                $text_required = "Undefined";
        }
        return $text_required;
    }
}
