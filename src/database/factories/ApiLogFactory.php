<?php

namespace Database\Factories;

use App\Models\Crew;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ApiLog>
 */
class ApiLogFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'user_id' => User::where("email", env("DEFAULT_EMAIL"))->first()->id,
            'application_id' => "seeder",
            'api_type' => "seeder",
            'http_method' => "POST",
            'model' => "Refugee",
            'ip' => $this->faker->ipv4,
            'crew_id' => Crew::getDefaultCrewId(),
        ];
    }
}
