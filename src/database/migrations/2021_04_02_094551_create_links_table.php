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
            $table->uuid( "id")
                ->primary()
                ->unique();
            $table->datetime( "date");
            $table->boolean( "deleted");
            $table->foreignUuid( "relation");
            $table->foreignUuid( "refugee1_id");
            $table->foreignUuid( "refugee2_id");
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
