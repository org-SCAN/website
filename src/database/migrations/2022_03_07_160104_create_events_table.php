<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->uuid("id")->primary();
            $table->string("name")->unique();
            $table->foreignUuid("event_type_id");
            $table->foreignUuid("event_subtype_id")->nullable();
            $table->foreignUuid("country_id")->nullable();
            $table->string("location_details")->nullable();
            $table->date("start_date")->nullable();
            $table->date("stop_date")->nullable();
            $table->string("coordinates")->nullable();
            $table->longText("description")->nullable();
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
        Schema::dropIfExists('events');
    }
}
