<?php

namespace Database\Factories;

use App\Models\ListMatchingAlgorithm;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Crew>
 */
class CrewFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => $this->faker->unique()->name,
            'duplicate_algorithm_id' => ListMatchingAlgorithm::getDefault()->id,
        ];
    }
}
