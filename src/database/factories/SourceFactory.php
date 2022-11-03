<?php

namespace Database\Factories;

use App\Models\ListSourceType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Source>
 */
class SourceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'source_type_id' => ListSourceType::inRandomOrder()->first()->id,
            'trust' => $this->faker->randomFloat(2, 0, 10),
            'reference' => $this->faker->text,
        ];
    }
}
