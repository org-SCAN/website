<?php

namespace Database\Factories;

use App\Models\ListSourceType;
use App\Models\Source;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Source>
 */
class SourceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    public function definition(
    ): array
    {
        return [
            'name' => $this->faker->name,
            'source_type_id' => ListSourceType::inRandomOrder()->first()->id,
            'trust' => 1,
            'reference' => $this->faker->text,
        ];
    }
}
