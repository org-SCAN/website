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
            $table->string("displayed_value")->nullable()->default(null);
            $table->string("key_value")->default('id');
            $table->string("name") //refers to the model
                ->unique();
            $table->boolean("show")->default(1);

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
