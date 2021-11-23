<?php

namespace App\Models;

use App\Models\Field;
use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Faker\Factory as Faker;

class FieldRefugee extends Pivot
{
    use HasFactory, Uuids;


    protected $table = "field_refugee";

    /**
     * The data type of the auto-incrementing ID.
     *
     * @var string
     */
    protected $keyType = 'string';

    /**
     * Indicates if the model's ID is auto-incrementing.
     *
     * @var bool
     */

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    public $incrementing = false;

    public static function random_fields(){
        $faker = Faker::create();
        return [
            Field::where("label",'unique_id')->first()->id =>
                ["id" => $faker->uuid, "value" => $faker->regexify('[A-Z]{3}-[0-9]{6}')],
            Field::where("label",'nationality')->first()->id =>
                ["id" => $faker->uuid, "value" => Country::inRandomOrder()->first()->id ],
            Field::where("label",'full_name')->first()->id =>
                ["id" => $faker->uuid, "value" => $faker->name],
            Field::where("label",'alias')->first()->id  =>
                ["id" => $faker->uuid, "value" => $faker->name],
            Field::where("label",'other_names')->first()->id =>
                ["id" => $faker->uuid, "value" => $faker->name],
            Field::where("label",'mothers_names')->first()->id =>
                ["id" => $faker->uuid, "value" => $faker->name],
            Field::where("label",'fathers_names')->first()->id  =>
                ["id" => $faker->uuid, "value" => $faker->name],
            Field::where("label",'role')->first()->id =>
                ["id" => $faker->uuid, "value" => Role::inRandomOrder()->first()->id],
            Field::where("label",'age')->first()->id =>
                ["id" => $faker->uuid, "value" => $faker->numberBetween(1,120)],
            Field::where("label",'birth_date')->first()->id =>
                ["id" => $faker->uuid, "value" => $faker->date("Y-m-d", $max = 'now')],
            Field::where("label",'date_last_seen')->first()->id => ["id" => $faker->uuid, "value" => $faker->date("Y-m-d", $min = '-2 years', $max = "now")],
            Field::where("label",'birth_place')->first()->id => ["id" => $faker->uuid, "value" => $faker->city],
            Field::where("label",'gender')->first()->id => ["id" => $faker->uuid, "value" => Gender::inRandomOrder()->first()->id],
            Field::where("label",'passport_number')->first()->id => ["id" => $faker->uuid, "value" => $faker->regexify('[A-Z]{3}-[0-9]{6}-[A-Z]{8}')],
            Field::where("label",'embarkation_date')->first()->id => ["id" => $faker->uuid, "value" => $faker->date("Y-m-d", $max = 'now')],
            Field::where("label",'detention_place')->first()->id => ["id" => $faker->uuid, "value" => $faker->randomElement(['Libya', '', 'Aleppo', 'Sahara'])],
            Field::where("label",'embarkation_place')->first()->id => ["id" => $faker->uuid, "value" => $faker->randomElement(['Libya', 'Roubaix', 'Tunisia', 'Algeria'])],
            Field::where("label",'destination')->first()->id => ["id" => $faker->uuid, "value" => $faker->randomElement(['France', 'Spain', 'Italy', 'Greece'])],
            Field::where("label",'route')->first()->id => ["id" => $faker->uuid, "value" => Route::inRandomOrder()->first()->id],
            Field::where("label",'residence')->first()->id => ["id" => $faker->uuid, "value" => Country::inRandomOrder()->first()->id],
        ];
    }
}
