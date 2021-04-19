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

    public function __construct()
    {

        $this->table_name = "countries";
    }

    public function up()
    {
        Schema::create($this->table_name, function (Blueprint $table) {
            $table->uuid("id")
                ->unique()
                ->primary();
            $table->string("ISO2");
            $table->string("ISO3");
            $table->string("short");
            $table->string("full");
            $table->timestamps();
            $table->boolean("deleted")
                ->default(0);
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
