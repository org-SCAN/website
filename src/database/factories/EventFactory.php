<?php

namespace Database\Factories;

use App\Models\ApiLog;
use App\Models\Event;
use App\Models\ListCountry;
use App\Models\ListEventType;
use App\Models\User;
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
    public function definition()
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

        return [
            "name" => $this->faker->unique()->name,
            "event_type_id" => ListEventType::inRandomOrder()->first()->id,
            "event_subtype_id" => $this->faker->uuid,
            "country_id" => ListCountry::inRandomOrder()->first()->id,
            "location_details" => $this->faker->streetAddress,
            "start_date" => $this->faker->dateTimeBetween('-10 week', '-5 week')->format('Y-m-d'),
            "stop_date" => $this->faker->dateTimeBetween('-4 week', '+1 week')->format('Y-m-d'),
            "coordinates" => $coordinates,
            "description" => $this->faker->realText,
            'api_log' => $log->id,
        ];
    }
}
