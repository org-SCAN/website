<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateListControlsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('list_controls', function (Blueprint $table) {
            $table->uuid("id")
                ->unique()
                ->primary();
            $table->timestamps();
            $table->string("title");
            $table->string("displayed_value");
            $table->string("key_value");
            $table->string("name")
                ->unique();

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
        Schema::dropIfExists('list_controls');
    }
}
