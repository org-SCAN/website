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

    public function __construct()
    {

        // read the json file to get the values
        $this->table_name = "fields";
    }

    public function up()
    {
        Schema::create($this->table_name, function (Blueprint $table) {
            $table->uuid( "id")
                ->unique()
                ->primary();
            $table->string("title");
            $table->string("label");
            $table->string("placeholder")
                ->nullable();
            $table->uuid("data_type_id")->nullable();
            //$table->string( "html_data_type");
            //$table->string( "database_type");
           // $table->string( "android_type");
            $table->string( "linked_list")
                ->nullable();
            $table->integer( "status");
            $table->integer( "required");
           // $table->string( "attribute")->nullable();
            $table->integer("order")
                ->default(100);
            $table->string("validation_laravel")
                ->nullable();
            $table->foreignUuid("crew_id");
            $table->boolean("best_descriptive_value")
                ->default(0)
                ->nullable();
            $table->boolean("descriptive_value")
                ->default(0)
                ->nullable();
            $table->foreignUuid('api_log');
            $table->softDeletes();
            $table->timestamps();
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

