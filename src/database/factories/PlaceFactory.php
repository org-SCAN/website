<?php

namespace Database\Factories;

use App\Models\Place;
use Illuminate\Database\Eloquent\Factories\Factory;

class PlaceFactory extends Factory
{
    protected $model = Place::class;

    public function definition(): array
    {
        return [
            "name" => $this->faker->unique()->name,
            "lat" => $this->faker->latitude,
            "lon" => $this->faker->longitude,
            "description" => $this->faker->realText,
        ];
    }
}
