<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFieldsTable extends Migration
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
        $this->table_name = "fields";
        $this->path_role_json =config('jsonDataset.path')."/".$this->table_name.".json";
    }

    public function up()
    {
        Schema::dropIfExists($this->table_name);
        Schema::create($this->table_name, function (Blueprint $table) {
            $table->uuid( "id")
                ->unique()
                ->primary();
            $table->string( "title");
            $table->string( "label");
            $table->string( "placeholder")
                ->nullable();
            $table->string( "html_data_type");
            $table->string( "database_type");
            $table->string( "UI_type");
            $table->string( "linked_list")
                ->nullable();
            $table->integer( "status");
            $table->integer( "required");
            $table->string( "attribute")
                ->nullable();
            $table->integer("order")
                ->default(100);
            $table->string("validation_laravel")
                ->nullable();
            $table->boolean("deleted")
                ->default(0);
            $table->timestamps();
        });



        $obj_json = file_get_contents($this->path_role_json);
        // interpret the json format as an array
        $array_json = json_decode($obj_json, true);
        // make the inserts
        foreach($array_json as $fields)
        {
            $to_store = array();
            $to_store["id"] = (string)Str::uuid();
            foreach ($fields as $keyField => $fieldValue) {
                $to_store[$keyField] = $fieldValue;
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

