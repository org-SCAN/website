<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRefugeesTable extends Migration
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
        $this->table_name = "refugees";
        $this->path_role_json =config('jsonDataset.path')."/"."fields".".json";
    }

    public function up()
    {

        Schema::dropIfExists($this->table_name);
        Schema::create($this->table_name, function (Blueprint $table) {
            $obj_json = file_get_contents($this->path_role_json);
            // interpret the json format as an array
            $array_json = json_decode($obj_json, true);
            $table->string($this->table_name . "__" . "id", 36)->unique();
            $table->date($this->table_name . "__" . "date");
            $table->boolean($this->table_name . "__" . "deleted");
            $table->timestamps();
            foreach ($array_json as $field) {
                $table->{$field["fields__database_type"]}($this->table_name . "__" .$field["fields__label"]);
            }
        });
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

