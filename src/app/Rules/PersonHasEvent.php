<?php

namespace App\Rules;

use App\Http\Requests\StoreLinkRequest;
use App\Models\Refugee;
use Illuminate\Contracts\Validation\InvokableRule;

class PersonHasEvent implements InvokableRule
{
    public Refugee|null $from;
    public Refugee|null $to;
    public array $everyone;

    /**
     * Run the validation rule.
     *
     * @param string $attribute
     * @param mixed $value
     * @param \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString $fail
     * @return void
     */

    public function __construct(StoreLinkRequest $request)
    {
        $this->everyone["from"] = $request->input('everyoneFrom');
        $this->everyone["to"] = $request->input('everyoneTo');
        $this->from = Refugee::find($request->input('from'));
        $this->to = Refugee::find($request->input('to'));


    }

    public function __invoke($attribute, $value, $fail)
    {
        if (!empty($this->everyone["from"]) || !empty($this->everyone["to"])) {
            // at least one of the two is set, so I have to check if the user has an event
            $person = $this->{$attribute};

            //I have to check if the person has an event and if the person is not empty : if empty -> normal because checkbox -> don't fail. If not empty && no event -> fail
            if (!empty($person) && !$person->hasEvent()) {
                $fail('This person isn\'t associated to any event');
            }
        }

    }
}
