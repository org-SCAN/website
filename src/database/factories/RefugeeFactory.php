<?php

namespace Database\Factories;


use App\Models\Crew;
use App\Models\Refugee;
use Illuminate\Database\Eloquent\Factories\Factory;

class RefugeeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Refugee::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(
    ): array
    {
        return [
            'id' => $this->faker->uuid,
            'crew_id' => Crew::getDefaultCrewId(),
            'date' => $this->faker->date("Y-m-d",
                $max = 'now',
                $min = '- 2 months'),
        ];
    }
}
