<?php

namespace Database\Factories;

use App\Models\Country;
use App\Models\FieldRefugee;
use App\Models\Field;
use App\Models\Gender;
use App\Models\Role;
use App\Models\Route;
use Illuminate\Database\Eloquent\Factories\Factory;

class FieldRefugeeFactory extends Factory
{

    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = FieldRefugee::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            Field::where("label",'unique_id')->first()->id =>
                ["value" => $this->faker->regexify('[A-Z]{3}-[0-9]{6}')],
            Field::where("label",'nationality')->first()->id =>
                ["value" => Country::inRandomOrder()->first()->id ],
            Field::where("label",'full_name')->first()->id =>
                ["value" => $this->faker->name],
            Field::where("label",'alias')->first()->id  =>
                ["value" => $this->faker->name],
            Field::where("label",'other_names')->first()->id =>
                ["value" => $this->faker->name],
            Field::where("label",'mothers_names')->first()->id =>
                ["value" => $this->faker->name],
            Field::where("label",'fathers_names')->first()->id  =>
                ["value" => $this->faker->name],
            Field::where("label",'role')->first()->id =>
                ["value" => Role::inRandomOrder()->first()->id],
            Field::where("label",'age')->first()->id =>
                ["value" => $this->faker->numberBetween(1,120)],
            Field::where("label",'birth_date')->first()->id =>
                ["value" => $this->faker->date("Y-m-d", $max = 'now')],
            Field::where("label",'date_last_seen')->first()->id => ["value" => $this->faker->date("Y-m-d", $min = '-2 years', $max = "now")],
            Field::where("label",'birth_place')->first()->id => ["value" => $this->faker->city],
            Field::where("label",'gender')->first()->id => ["value" => Gender::inRandomOrder()->first()->id],
            Field::where("label",'passport_number')->first()->id => ["value" => $this->faker->regexify('[A-Z]{3}-[0-9]{6}-[A-Z]{8}')],
            Field::where("label",'embarkation_date')->first()->id => ["value" => $this->faker->date("Y-m-d", $max = 'now')],
            Field::where("label",'detention_place')->first()->id => ["value" => $this->faker->randomElement(['Libya', '', 'Aleppo', 'Sahara'])],
            Field::where("label",'embarkation_place')->first()->id => ["value" => $this->faker->randomElement(['Libya', 'Roubaix', 'Tunisia', 'Algeria'])],
            Field::where("label",'destination')->first()->id => ["value" => $this->faker->randomElement(['France', 'Spain', 'Italy', 'Greece'])],
            Field::where("label",'route')->first()->id => ["value" => Route::inRandomOrder()->first()->id],
            Field::where("label",'residence')->first()->id => ["value" => Country::inRandomOrder()->first()->id],
        ];
    }
}
