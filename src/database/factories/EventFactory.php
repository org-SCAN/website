<?php

namespace Database\Factories;

use App\Models\Event;
use App\Models\ListCountry;
use App\Models\ListEventType;
use Illuminate\Database\Eloquent\Factories\Factory;

class EventFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Event::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(
    )
    {

        $coordinates = json_encode([
            "lat" => $this->faker->latitude,
            "long" => $this->faker->longitude,
        ]);

        $polygon = [];

        for ($i = 0; $i < 4; $i++) // create a polygon with 4 points
        {
            $polygon[$i] = [
                "lat" => $this->faker->latitude,
                "long" => $this->faker->longitude,
            ];
        }
        $area = json_encode([
            "polygons" => [[$polygon]],
        ]);

        return [
            "name" => $this->faker->unique()->name,
            "event_type_id" => ListEventType::inRandomOrder()->first()->id,
            "event_subtype_id" => $this->faker->uuid,
            "country_id" => ListCountry::inRandomOrder()->first()->id,
            "location_details" => $this->faker->streetAddress,
            "start_date" => $this->faker->dateTimeBetween('-10 week',
                '-5 week')->format('Y-m-d'),
            "stop_date" => $this->faker->dateTimeBetween('-4 week',
                '+1 week')->format('Y-m-d'),
            "coordinates" => $coordinates,
            "area" => $area,
            "description" => $this->faker->realText,
        ];
    }
}
