<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCountriesTable extends Migration
{
        /**
     * Run the migrations.
     *
     * @return void
     */
    protected $table_name;
    protected $path_role_json;

    public function __construct()
    {

        // read the json file to get the values
        $this->table_name = "countries";
        $this->path_role_json =config('jsonDataset.path')."/".$this->table_name.".json";
    }

    public function up()
    {
        Schema::dropIfExists($this->table_name);
        Schema::create($this->table_name, function (Blueprint $table) {
            $table->uuid("id")
                ->unique()
                ->primary();
            $table->string("ISO2");
            $table->string("ISO3");
            $table->string("short");
            $table->string("full");
            $table->timestamps();
        });



        $obj_json = file_get_contents($this->path_role_json);
        // interpret the json format as an array
        $array_json = json_decode($obj_json, true);

        // make the inserts
        foreach($array_json as $country)
        {
            $to_store = array();
            $to_store["id"] = (string)Str::uuid();
            foreach ($country as $countryKey => $countryValue) {
                $to_store[$countryKey] = $countryValue;
            }
          DB::table($this->table_name)->insert($to_store);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists($this->table_name);
    }
}
