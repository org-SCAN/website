<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCountryTable extends Migration
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
        $this->table_name = "country";
        $this->path_role_json =config('jsonDataset.path')."/".$this->table_name.".json";
    }

    public function up()
    {
        Schema::dropIfExists($this->table_name);
        Schema::create($this->table_name, function (Blueprint $table) {
            $table->string($this->table_name . "__" . "id", 36)->unique();
            $table->index($this->table_name . "__" . "ISO2");
            $table->index($this->table_name . "__" . "ISO3");
            $table->index($this->table_name . "__" . "short");
            $table->index($this->table_name . "__" . "full");
        });


        
        $obj_json = file_get_contents($this->path_role_json);
        // interpret the json format as an array
        $array_json = json_decode($obj_json, true);

        // make the inserts
        foreach($array_json as $gender)
        {
          DB::table($this->table_name)->insert(
            [
              $this->table_name."__"."id"  => (string)Str::uuid(),
              $this->table_name."__"."ISO2" => $gender[$this->table_name."__"."ISO2"],
              $this->table_name."__"."ISO3" => $gender[$this->table_name."__"."ISO3"],
              $this->table_name."__"."short" => $gender[$this->table_name."__"."short"],
              $this->table_name."__"."full" => $gender[$this->table_name."__"."full"]
            ]
          );
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
