<?php

namespace Database\Factories;

use App\Models\Crew;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Field>
 */
class FieldFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            "title" => $this->faker->title,
            "label" => $this->faker->title,
            "placeholder" => $this->faker->sentence,
            "database_type" => "string",
            "html_data_type" => "text",
            "android_type" => 'EditText',
            "crew_id" => Crew::inrandomorder()->first()->id,
            "required" => $this->faker->numberBetween(0, 3),
            "status" => $this->faker->numberBetween(0, 2),
            "order" => $this->faker->numberBetween(1, 50),
            "api_log" => $this->faker->uuid,
        ];
    }
}
