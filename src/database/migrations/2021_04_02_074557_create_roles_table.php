<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRolesTable extends Migration
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
        $this->table_name = "roles";
        $this->path_role_json =config('jsonDataset.path')."/".$this->table_name.".json";
    }

    public function up()
    {
        Schema::dropIfExists($this->table_name);
        Schema::create($this->table_name, function (Blueprint $table) {
            $table->uuid("id")
                ->unique()
                ->primary();
            $table->string("short");
            $table->string("descr");
            $table->timestamps();
        });



        $obj_json = file_get_contents($this->path_role_json);
        // interpret the json format as an array
        $array_json = json_decode($obj_json, true);

        // make the inserts
        foreach($array_json as $role)
        {
            $to_store = array();
            $to_store["id"] = (string)Str::uuid();
            foreach ($role as $roleKey => $roleValue) {
                $to_store[$roleKey] = $roleValue;
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
