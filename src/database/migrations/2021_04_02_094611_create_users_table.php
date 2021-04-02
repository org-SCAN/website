<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
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
        $this->table_name = "users";
    }

    public function up()
    {
        
        Schema::dropIfExists($this->table_name);
        Schema::create($this->table_name, function (Blueprint $table) {
            $table->string($this->table_name . "__" . "id", 36)->unique();
            $table->date($this->table_name . "__" . "last_log_date");
            $table->date($this->table_name . "__" . "creation_log_date");
            $table->boolean($this->table_name . "__" . "deleted");
            $table->string($this->table_name . "__" . "relation");
            $table->string($this->table_name . "__" . "rights");
            $table->string($this->table_name . "__" . "password");
            $table->email($this->table_name . "__" . "email");
            $table->string($this->table_name . "__" . "first_name");
            $table->string($this->table_name . "__" . "last_name");
            $table->string($this->table_name . "__" . "country");
            $table->string($this->table_name . "__" . "language");
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
