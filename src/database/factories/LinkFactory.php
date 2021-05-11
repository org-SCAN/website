<?php

namespace Database\Factories;

use App\Models\Link;
use App\Models\Refugee;
use App\Models\Relation;
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
    public function definition()
    {
        return [
            'date' => $this->faker->date("Y-m-d", $max = 'now', $min = '- 2 months'),
            'relation' => Relation::inRandomOrder()->first()->id,
            'from' => Refugee::inRandomOrder()->first()->id,
            'to' => Refugee::inRandomOrder()->first()->id,
            'api_log' => "seeder",
        ];
    }
}
