<?php

namespace App\Rules;

use App\Http\Requests\StoreLinkRequest;
use App\Models\Link;
use Closure;
use Illuminate\Contracts\Validation\InvokableRule;
use Illuminate\Translation\PotentiallyTranslatedString;

class RelationDoesNotAlreadyExist implements InvokableRule
{
    /**
     * Run the validation rule.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @param  Closure(string): PotentiallyTranslatedString  $fail
     * @return void
     */

    public function __construct(StoreLinkRequest $request){

        $this->from = $request->input('from');
        $this->to = $request->input('to');
        $this->relation_id = $request->input('relation_id');


    }
    public function __invoke($attribute, $value, $fail)
    {
        // check that the relation does not already exist
        $relation = Link::where("from", $this->from)
            ->where("to", $this->to)
            ->where("relation_id", $this->relation_id)
            ->first();
        //I have to check if the person has an event and if the person is not empty : if empty -> normal because checkbox -> don't fail. If not empty && no event -> fail
        if (!empty($relation)) {
            $fail('This relation already exists.');
        }
    }
}
