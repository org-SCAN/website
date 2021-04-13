<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLinksTable extends Migration
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
        $this->table_name = "links";
    }

    public function up()
    {
        
        Schema::dropIfExists($this->table_name);
        Schema::create($this->table_name, function (Blueprint $table) {
            $table->string($this->table_name . "__" . "id", 36)->unique();
            $table->date($this->table_name . "__" . "date");
            $table->boolean($this->table_name . "__" . "deleted");
            $table->string($this->table_name . "__" . "relation");
            $table->string($this->table_name . "__" . "refugee1_id");
            $table->string($this->table_name . "__" . "refugee2_id");
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
