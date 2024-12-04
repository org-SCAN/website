<?php

namespace Database\Factories;

use App\Models\Crew;
use App\Models\Link;
use App\Models\ListRelation;
use App\Models\Refugee;
use Illuminate\Database\Eloquent\Factories\Factory;

class LinkFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Link::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(
    )
    {

        return [
            'date' => $this->faker->date(),
            'relation_id' => ListRelation::inRandomOrder()->first()->id,
            'from' => Refugee::inRandomOrder()->first()->id,
            'to' => Refugee::inRandomOrder()->first()->id,
            'crew_id' => Crew::getDefaultCrewId(),
        ];
    }
}
