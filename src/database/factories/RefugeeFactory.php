<?php

namespace Database\Factories;


use App\Models\ApiLog;
use App\Models\Refugee;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class RefugeeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Refugee::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {

        $log["user"] = User::where("email", env("DEFAULT_EMAIL"))->first()->id;
        $log["application_id"] = "seeder";
        $log["api_type"] = "seeder";
        $log["http_method"] = "POST";
        $log["model"] = "Refugee";
        $log["ip"] = "127.0.0.1";
        $log["crew_id"] = User::where("email", env("DEFAULT_EMAIL"))->first()->crew->id;

        $log = ApiLog::create($log);

        return [
            'id' => $this->faker->uuid,
            'date' => $this->faker->date("Y-m-d", $max = 'now', $min = '- 2 months'),
            'api_log' => $log->id,
        ];
    }
}
