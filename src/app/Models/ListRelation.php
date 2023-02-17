<?php

namespace App\Models;

class ListRelation extends ListControl
{

    /**
     * Relation with the relation type
     */
    public function type() {
        return $this->belongsTo(ListRelationType::class,
            "relation_type_id");
    }
}
