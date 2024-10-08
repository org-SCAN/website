<?php

namespace Database\Factories;

use App\Models\Place;
use Illuminate\Database\Eloquent\Factories\Factory;

class PlaceFactory extends Factory
{
    protected $model = Place::class;

    public function definition(): array
    {

        $coordinates = json_encode([
            "lat" => $this->faker->latitude,
            "long" => $this->faker->longitude,
        ]);

        $polygon = [];

        for ($i = 0; $i < 4; $i++)
            // create a polygon with 4 points
            $polygon[$i] = [
                "lat" => $this->faker->latitude,
                "long" => $this->faker->longitude,
            ];
        $area = json_encode([
            "polygons" => [[$polygon]]
        ]);

        return [
            "name" => $this->faker->unique()->name,
            "coordinates" => $coordinates,
            "description" => $this->faker->realText,
            "area" => $area,
        ];
    }
}
