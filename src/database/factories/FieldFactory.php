<?php

namespace Database\Factories;

use App\Models\Crew;
use App\Models\Field;
use App\Models\ListDataType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Field>
 */
class FieldFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition() : array
    {
        return [
            "title" => $this->faker->title,
            "label" => $this->faker->title,
            "placeholder" => $this->faker->sentence,
            "data_type_id" => ListDataType::inRandomOrder()->first()->id,
            "crew_id" => Crew::inrandomorder()->first()->id,
            "required" => $this->faker->numberBetween(0, 3),
            "status" => $this->faker->numberBetween(0, 2),
            "order" => $this->faker->numberBetween(1, 50),
        ];
    }
}
