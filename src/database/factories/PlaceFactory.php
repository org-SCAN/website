<?php

namespace Database\Factories;

use App\Models\Place;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\ApiLog;
use App\Models\User;

class PlaceFactory extends Factory
{
    protected $model = Place::class;

    public function definition(): array
    {
        $log["user_id"] = User::where("email", env("DEFAULT_EMAIL"))->first()->id;
        $log["application_id"] = "seeder";
        $log["api_type"] = "seeder";
        $log["http_method"] = "POST";
        $log["model"] = "Event";
        $log["ip"] = "127.0.0.1";
        $log["crew_id"] = User::where("email", env("DEFAULT_EMAIL"))->first()->crew->id;

        $log = ApiLog::create($log);

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
            "api_log" => $log->id,
        ];
    }
}
