<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateListRolesTable extends Migration
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
        $this->table_name = "list_roles";
    }

    public function up()
    {
        Schema::create($this->table_name, function (Blueprint $table) {
            $table->uuid("id")
                ->unique()
                ->primary();
            $table->string("short");
            $table->string("descr");
            $table->string("key");
            $table->timestamps();
            $table->softDeletes();
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
