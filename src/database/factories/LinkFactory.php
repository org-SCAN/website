<?php

namespace Database\Factories;

use App\Models\Link;
use App\Models\Refugee;
use App\Models\Role;
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
            'date' => $this->faker->date("Y-m-d", $max = 'now', $min='- 2 months'),
            'relation' => Role::inRandomOrder()->first()->id,
            'refugee1_id'=> Refugee::inRandomOrder()->first()->id,
            'refugee2_id'=> Refugee::inRandomOrder()->first()->id
        ];
    }
}
