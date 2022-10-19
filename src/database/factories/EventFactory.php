<?php

namespace Database\Factories;

use App\Models\ApiLog;
use App\Models\Event;
use App\Models\ListCountry;
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

        return [
            "name" => $this->faker->city,
            "event_type_id" => $this->faker->uuid,
            "event_subtype_id" => $this->faker->uuid,
            "country_id" => ListCountry::inRandomOrder()->first()->id,
            "location_details" => $this->faker->streetAddress,
            "start_date" => $this->faker->dateTime,
            "stop_date" => $this->faker->dateTime,
            "latitude" => $this->faker->latitude,
            "longitude" => $this->faker->longitude,
            "description" => $this->faker->realText,
            'apiLog_id' => $log->id,
        ];
    }
}
