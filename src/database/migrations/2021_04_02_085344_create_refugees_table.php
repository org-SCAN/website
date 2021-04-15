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
            $table->uuid("id")
                ->unique()
                ->primary();
            $table->date("date");
            $table->boolean("deleted");
            $table->timestamps();
            foreach ($array_json as $field) {
                if($field["required"] !=1){
                    $table->{$field["database_type"]}($field["label"])->nullable();
                }
                $table->{$field["database_type"]}($field["label"]);
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

