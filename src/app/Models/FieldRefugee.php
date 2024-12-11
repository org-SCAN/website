<?php

namespace App\Models;

use App\Traits\ModelEventsLogs;
use App\Traits\Uuids;
use ESolution\DBEncryption\Traits\EncryptedAttribute;
use Faker\Factory as Faker;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;


class FieldRefugee extends Pivot
{
    use HasFactory, Uuids, EncryptedAttribute, ModelEventsLogs;


    /**
     * Indicates if the model's ID is auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;
    protected $table = "field_refugee";
    /**
     * The data type of the auto-incrementing ID.
     *
     * @var string
     */
    protected $keyType = 'string';
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be encrypted on save.
     *
     * @var array
     */
    protected $encryptable = [
        'value'
    ];


    public static function random_fields(){
        $faker = Faker::create();
        return [
            Field::whereEncrypted("label", 'unique_id')->first()->id =>
                ["id" => $faker->uuid, "value" => $faker->regexify('[A-Z]{3}-[0-9]{6}')],
            Field::whereEncrypted("label", 'nationality')->first()->id =>
                ["id" => $faker->uuid, "value" => ListCountry::inRandomOrder()->first()->id],
            Field::whereEncrypted("label", 'full_name')->first()->id =>
                ["id" => $faker->uuid, "value" => $faker->name],
            Field::whereEncrypted("label", 'alias')->first()->id =>
                ["id" => $faker->uuid, "value" => $faker->name],
            Field::whereEncrypted("label", 'other_names')->first()->id =>
                ["id" => $faker->uuid, "value" => $faker->name],
            Field::whereEncrypted("label", 'mothers_names')->first()->id =>
                ["id" => $faker->uuid, "value" => $faker->name],
            Field::whereEncrypted("label", 'fathers_names')->first()->id =>
                ["id" => $faker->uuid, "value" => $faker->name],
            Field::whereEncrypted("label", 'role')->first()->id =>
                ["id" => $faker->uuid, "value" => ListRole::inRandomOrder()->first()->id],
            Field::whereEncrypted("label", 'age')->first()->id =>
                ["id" => $faker->uuid, "value" => $faker->numberBetween(1, 120)],
            Field::whereEncrypted("label", 'birth_date')->first()->id =>
                ["id" => $faker->uuid, "value" => $faker->date("Y-m-d", $max = 'now')],
            Field::whereEncrypted("label", 'date_last_seen')->first()->id => ["id" => $faker->uuid, "value" => $faker->date("Y-m-d", $min = '-2 years', $max = "now")],
            Field::whereEncrypted("label", 'birth_place')->first()->id => ["id" => $faker->uuid, "value" => $faker->city],
            Field::whereEncrypted("label", 'gender')->first()->id => ["id" => $faker->uuid, "value" => ListGender::inRandomOrder()->first()->id],
            Field::whereEncrypted("label", 'passport_number')->first()->id => ["id" => $faker->uuid, "value" => $faker->regexify('[A-Z]{3}-[0-9]{6}-[A-Z]{8}')],
            Field::whereEncrypted("label", 'embarkation_date')->first()->id => ["id" => $faker->uuid, "value" => $faker->date("Y-m-d", $max = 'now')],
            Field::whereEncrypted("label", 'detention_place')->first()->id => ["id" => $faker->uuid, "value" => $faker->randomElement(['Libya', '', 'Aleppo', 'Sahara'])],
            Field::whereEncrypted("label", 'embarkation_place')->first()->id => ["id" => $faker->uuid, "value" => $faker->randomElement(['Libya', 'Roubaix', 'Tunisia', 'Algeria'])],
            Field::whereEncrypted("label", 'destination')->first()->id => ["id" => $faker->uuid, "value" => $faker->randomElement(['France', 'Spain', 'Italy', 'Greece'])],
            Field::whereEncrypted("label", 'route')->first()->id => ["id" => $faker->uuid, "value" => ListRoute::inRandomOrder()->first()->id],
            Field::whereEncrypted("label", 'residence')->first()->id => ["id" => $faker->uuid, "value" => ListCountry::inRandomOrder()->first()->id],
        ];
    }
}
